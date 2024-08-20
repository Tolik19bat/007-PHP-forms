<?php
declare(strict_types=1);

function redirectToForm(): void {
    header('Location: ./index.html');
    exit();
}

function createUploadDirectory(string $directory): void {
    if (!is_dir($directory)) {
        if (!mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException("Не удалось создать директорию $directory");
        }
    }
}

// Включаем строгие проверки
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Проверка, было ли заполнено поле file_name
    $fileName = $_POST['file_name'];
    if (!empty($fileName)) {
        redirectToForm();
    }

    // Проверка, был ли передан файл
    if (!isset($_FILES['content']) || $_FILES['content']['error'] !== UPLOAD_ERR_OK) {
        redirectToForm();
    }

    // Создание каталога для загрузки, если его не существует
    $uploadDir = 'upload/';
    createUploadDirectory($uploadDir);

    // Безопасное получение имени файла из формы
    $fileName = basename($fileName);
    $filePath = $uploadDir . $fileName;

    // Перемещение загруженного файла в нужное место
    if (move_uploaded_file($_FILES['content']['tmp_name'], $filePath)) {
        // Отображение полного пути и размера файла
        echo "Файл успешно загружен!<br>";
        echo "Полный путь: " . realpath($filePath) . "<br>";
        echo "Размер файла: " . filesize($filePath) . " байт<br>";
        echo "<a href='$filePath'>Скачать файл</a>"; // Ссылка на скачивание
    } else {
        echo "Ошибка при загрузке файла.";
    }
} else {
    redirectToForm();
}
