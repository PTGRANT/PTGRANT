<?php
// save.php
define('APPS_FILE', __DIR__ . '/applications.json');
define('MSGS_FILE', __DIR__ . '/messages.json');

if (!file_exists(APPS_FILE)) {
    file_put_contents(APPS_FILE, json_encode([]));
}
if (!file_exists(MSGS_FILE)) {
    file_put_contents(MSGS_FILE, json_encode([]));
}

function get_applications() {
    $data = file_get_contents(APPS_FILE);
    return json_decode($data, true) ?: [];
}

function save_applications($apps) {
    file_put_contents(APPS_FILE, json_encode(array_values($apps), JSON_PRETTY_PRINT));
}

function save_new_application($data) {
    $apps = get_applications();
    $apps[] = $data;
    save_applications($apps);
}

function get_application_by_num($app_num) {
    $apps = get_applications();
    foreach ($apps as $app) {
        if (strtoupper($app['app_number']) === strtoupper($app_num)) {
            return $app;
        }
    }
    return null;
}

function update_application($app_num, $update_fields) {
    $apps = get_applications();
    foreach ($apps as $index => $app) {
        if (strtoupper($app['app_number']) === strtoupper($app_num)) {
            foreach ($update_fields as $key => $val) {
                $apps[$index][$key] = $val;
            }
            save_applications($apps);
            return $apps[$index];
        }
    }
    return null;
}

function get_messages($app_num = null) {
    $data = file_get_contents(MSGS_FILE);
    $msgs = json_decode($data, true) ?: [];
    if ($app_num !== null) {
        $filtered = [];
        foreach ($msgs as $m) {
            if (strtoupper($m['app_number']) === strtoupper($app_num)) {
                $filtered[] = $m;
            }
        }
        return $filtered;
    }
    return $msgs;
}

function add_message($app_num, $sender, $text) {
    $msgs = get_messages();
    $msgs[] = [
        'id' => time() . '_' . rand(100, 999),
        'app_number' => $app_num,
        'sender' => $sender,
        'message' => $text,
        'created_at' => date('Y-m-d H:i:s')
    ];
    file_put_contents(MSGS_FILE, json_encode($msgs, JSON_PRETTY_PRINT));
}
?>
