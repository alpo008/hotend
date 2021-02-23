<?php


namespace app\models;


use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\db\StaleObjectException;

/**
 * RelocationForm is the model behind the material relocation form.
 *
 * @property integer $materials_id
 * @property integer $stocks_id_old
 * @property integer $stocks_id_new
 * @property float $qty
 *
 * @property Materials $material
 * @property array $oldLocationsList
 * @property array $newLocationsList
 */
class RelocationForm extends Model
{
    public $materials_id;
    public $stocks_id_old;
    public $stocks_id_new;
    public $qty;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['materials_id', 'stocks_id_old', 'stocks_id_new', 'qty'], 'required'],
            [['materials_id', 'stocks_id_old', 'stocks_id_new', 'qty'], 'number'],
            [['stocks_id_new', 'stocks_id_old'], 'validateStocksId'],
            ['qty', 'validateQty']
        ];
    }

    /**
     * Stocks id validator
     */
    public function validateStocksId()
    {
        if ($this->stocks_id_new === $this->stocks_id_old){
            $this->addError('stocks_id_new',
            Yii::t('app', 'Source and target places can not be the same') . ' !');
        }
    }

    /**
     * Stocks id validator
     */
    public function validateQty()
    {
        $existingQty = Locations::find()
            ->select('qty')
            ->where(['materials_id' => $this->materials_id])
            ->andWhere(['stocks_id' => $this->stocks_id_old])
            ->scalar();

        if (!$existingQty || $existingQty < $this->qty){
            $this->addError('qty',
            Yii::t('app', 'Quantity can not be greater then exists on place') .
                ' ( ' . Yii::t('app', 'rest') . ': ' .
                $existingQty . ' ' . $this->material->unit .' ) !'
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'materials_id' => Yii::t('app', 'Material'),
            'stocks_id_old' => Yii::t('app', 'From where'),
            'stocks_id_new' => Yii::t('app', 'To where'),
            'qty' => Yii::t('app', 'Quantity'),
        ];
    }

    /**
     * Updates old and new locations quantities
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            $oldLocation = Locations::findOne([
                'materials_id' => $this->materials_id,
                'stocks_id' => $this->stocks_id_old
            ]);
            if ($oldLocation instanceof Locations && $oldLocation->qty >= $this->qty) {
                $transaction = Yii::$app->db->beginTransaction();
                $oldLocation->qty -= $this->qty;
                $newLocation = Locations::findOne([
                    'materials_id' => $this->materials_id,
                    'stocks_id' => $this->stocks_id_new
                ]);
                if ($newLocation instanceof Locations) {
                    $newLocation->qty += $this->qty;
                } else {
                    $newLocation = new Locations([
                        'materials_id' => $this->materials_id,
                        'stocks_id' => $this->stocks_id_new,
                        'qty' => $this->qty
                    ]);
                }
                if (!$oldLocation->qty) {
                    try {
                        $oldLocation->delete();
                    } catch (StaleObjectException $e) {
                        $transaction->rollBack();
                        return false;
                    } catch (\Throwable $e) {
                        $transaction->rollBack();
                        return false;
                    }
                } else {
                    if (!$oldLocation->save()) {
                        $transaction->rollBack();
                        return false;
                    }
                }
                if (!$newLocation->save()) {
                    $transaction->rollBack();
                    return false;
                }
                try {
                    $transaction->commit();
                } catch (Exception $e) {
                    return false;
                }
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * List of locations containing selected material [locations_id => stocks_placename]
     * @return array
     */
    public function getOldLocationsList()
    {
        $list = [];
        if ($this->material instanceof Materials) {
            if ($locations = $this->material->locations) {
                if (is_array($locations)) {
                    foreach ($locations as $location) {
                        if ($stock = $location->stocks) {
                            $list[$stock->id] = $stock->placename;
                        }
                    }
                }
            }
        }
        return $list;
    }

    /**
     * List of locations containing selected material [locations_id => stocks_placename]
     * @return array
     */
    public function getNewLocationsList()
    {
        if ($this->material instanceof Materials) {
            $locations = Stocks::find()
                ->select('DISTINCT(stocks.placename), locations.stocks_id')
                ->rightJoin('locations', 'stocks.id = locations.stocks_id')
                ->orderBy('stocks.placename')
                ->asArray()
                ->all();
            if (!empty($locations && is_array($locations))) {
                return array_column($locations, 'placename', 'stocks_id');
            }
        }
        return [];
    }

    /**
     * @return Materials|null
     */
    protected function getMaterial()
    {
        return Materials::findOne(['id' => $this->materials_id]);
    }
}