<?php
class Setting {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllSettings() {
        $stmt = $this->db->query("SELECT * FROM settings");
        $settings_array = $stmt->fetchAll();
        $settings = [];
        foreach ($settings_array as $setting) {
            $settings[$setting['setting_key']] = $setting['setting_value'];
        }
        return $settings;
    }

    public function updateSetting($key, $value) {
        $stmt = $this->db->prepare(
            "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE setting_value = ?"
        );
        return $stmt->execute([$key, $value, $value]);
    }
}