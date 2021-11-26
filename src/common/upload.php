<?php

function uploadImg($img_file, $key)
{
    try {
        switch ($img_file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('ファイルが選択されていません');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('ファイルサイズが大きすぎます');
            default:
                throw new RuntimeException('その他のエラーが発生しました');
        }

        $type = @exif_imagetype($img_file['tmp_name']);
        if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
            throw new RuntimeException('画像形式が未対応です');
        }

        $path = '../public/uploads/' . sha1_file($img_file['tmp_name']) . image_type_to_extension($type);
        if (!move_uploaded_file($img_file['tmp_name'], $path)) {
            throw new RuntimeException('ファイル保存時にエラーが発生しました');
        }

        chmod($path, 0644);

        return $path;
    } catch (RuntimeException $e) {

        global $err_msg;
        $err_msg[$key] = $e->getMessage();
    }
}
