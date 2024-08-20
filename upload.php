<?php
// Проверка, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Проверка, было ли заполнено поле file_name
    if (empty($_POST['file_name'])) {
        header('Location: index.html');
        exit();
    }

    // Проверка, был ли передан файл
    if (!isset($_FILES['content']) || $_FILES['content']['error'] !== UPLOAD_ERR_OK) {
        header('Location: index.html');
        exit();
    }

    // Создание каталога для загрузки, если его не существует
    $uploadDir = 'upload/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Получение имени файла из формы и его пути
    $fileName = basename($_POST['file_name']);
    $filePath = $uploadDir . $fileName;

    // Перемещение загруженного файла в нужное место
    if (move_uploaded_file($_FILES['content']['tmp_name'], $filePath)) {
        // Отображение полного пути и размера файла
        echo "Файл успешно загружен!<br>";
        echo "Полный путь: " . realpath($filePath) . "<br>";
        echo "Размер файла: " . filesize($filePath) . " байт";
    } else {
        echo "Ошибка при загрузке файла.";
    }
} else {
    header('Location: index.html');
    exit();
}