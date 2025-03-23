<?php
use yii\helpers\Html;

/** @var $users \app\models\User[] */
?>

<h1>Пользователи</h1>

<p><?= Html::a('Создать пользователя', ['create'], ['class' => 'btn btn-success']) ?></p>

<table border="1" cellpadding="8">
    <thead>
        <tr>
            <th>ID</th>
            <th>Логин</th>
            <th>Роль</th>
            <th>Создан</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user->id ?></td>
                <td><?= Html::encode($user->username) ?></td>
                <td><?= $user->is_admin ? 'Админ' : 'Пользователь' ?></td>
                <td><?= $user->created_at ?></td>
                <td>
                    <?= Html::a('Редактировать', ['update', 'id' => $user->id]) ?> |
                    <?= Html::a('Удалить', ['delete', 'id' => $user->id], ['data-method' => 'post']) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
