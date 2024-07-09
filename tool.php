<?php
echo "Hieu 3D\n\n\n\n\n";

while (true) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost/DU_AN_CA_NHAN/test');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Mobile Safari/537.36');
    $data = json_decode(curl_exec($ch));
    curl_close($ch);

    echo "\033[1;32m Đang GET dữ liệu từ db\r";

    if (count($data) == '0') {
        sleep(1);
        continue;
    }

    foreach ($data as $key) {
        $title = $key->title;
        $text = $key->text;
        $toemail = $key->user_send;
        $id = $key->id;
        $send = send_mail($toemail, $text, $title);
        if ($send->status == true) {
            echo "\033[1;32m Send success => $toemail                                      \n";
        } else {
            echo "\033[1;31m Send Loi: " . $send->message . "                              \n";
            continue;
        }

        $updateStatus = updateStatus($id);
        if ($updateStatus->status == true) {
            echo "\033[1;32m Update success => $toemail                                    \n";
        } else {
            echo "\033[1;31m Update Loi: " . $updateStatus->message . "                                   \n";
            continue;
        }
    }
}



function send_mail($toemail, $msg, $title)
{
    $data_arr = array(
        'emailcuatao' => 'hieudvph32592@fpt.edu.vn',
        'pass' => 'qfsioqmbqxkkekcs',
        'name' => 'ThongBaoGhiChu',
        'email' => $toemail,
        'message' => $msg,
        'title' => $title
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://mail.laikacafe.online/dvv.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Mobile Safari/537.36');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_arr);
    $data = json_decode(curl_exec($ch));
    curl_close($ch);
    return $data;
}
function updateStatus($id)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost/DU_AN_CA_NHAN/update-status?id=' . $id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Mobile Safari/537.36');
    $data = json_decode(curl_exec($ch));
    curl_close($ch);
    return $data;
}
