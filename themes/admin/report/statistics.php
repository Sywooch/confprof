<?php

use yii\widgets\ListView;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $data array */

usort(
    $data,
    function($a, $b) {
        return $a['conf'] > $b['conf'] ?
            1 :
            (
                $a['conf'] < $b['conf'] ?
                    -1 :
                    (
                        $a['sect'] > $b['sect'] ?
                            1 :
                            (
                                $a['sect'] < $b['sect'] ?
                                    -1 :
                                    0
                            )
                    )
            );
    }
);

?>

<table class="table table-bordered table-striped">
    <tbody>
    <tr><th>Конференция</th><th>Секция</th><th>Докладов</th><th>Лидеров</th><th>Соучастников</th><th>Консультантов</th><th>Гостей</th></tr>
<?php

echo ListView::widget([
    'dataProvider' => new ArrayDataProvider([
        'allModels' => $data,
        'key' => 'sect_id',
//        'sort' => [
//            'attributes' => [ 'sect_id', ],
//        ],
        'pagination' => [
            'pageSize' => 50,
        ],
    ]),
    'itemView' => function ($model, $key, $index, $widget) {
        echo '<tr>'
            . '<td>'.$model['conf'].'</td>'
            . '<td>'.$model['sect'].'</td>'
            . '<td>'.$model['doclads'].'</td>'
            . '<td>'.$model['leaders'].'</td>'
            . '<td>'.$model['cou_member'].'</td>'
            . '<td>'.$model['cou_consult'].'</td>'
            . '<td>'.$model['cou_guest'].'</td>'
            . '</tr>';
    },
]);

?>
    </tbody>
</table>