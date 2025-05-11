
# 📦 InstallThemeCommand 指令開發筆記

## ✅ 當前狀態
- 已開發完成 Blade Component 自動掃描與註冊功能。
- 使用者可透過以下指令安裝主題：

```bash
php artisan theme:install --package=Vendor --name=theme-name [--force]
```

- Component 資料會直接寫入 `installed_themes` 和 `theme_components` 資料表。

---

## 🛠️ 指令功能

| 功能           | 說明                              |
|---------------|-----------------------------------|
| --package     | 指定主題 package 的頂層名稱       |
| --name        | 指定主題名稱 (slug)               |
| --force       | 強制更新已安裝主題                  |
| Component 註冊 | 透過掃描 Blade Component 目錄自動註冊 |
| Component Type | 固定標記為 `blade` 便於後續識別    |

---

## 📚 未來擴充規劃

### 1. 安裝檔案規範
- 建立統一的 `install.php` 或 `manifest.json` 格式存放主題資訊。
- 範例：
```php
return [
    'name' => 'Lezada Theme',
    'version' => '1.0.0',
    'author' => 'pinpinAura Team',
    'description' => 'Lezada 電商主題。',
];
```

### 2. 自動主題掃描
- 掃描 `packages/*/themes-*` 路徑，自動列出可安裝主題清單。

### 3. 互動式安裝引導
- 透過 Artisan 問答互動，一步一步完成設定與安裝。

### 4. 轉為 Composer 安裝套件
- 包成 `pinpin/theme-installer` 工具包，提升可攜性與重複利用性。

---
