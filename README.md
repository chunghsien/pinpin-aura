# pinpinAura

pinpinAura 是一個基於 Laravel 與 React 的智慧型 CMS 與電商整合平台，結合 AI 技術提供高效內容管理與個性化購物體驗。

## 🛠️ 專案特色

* 採用 Laravel 後端架構，確保穩定、安全與可擴展。
* 採用 React 前端框架，提供高效能、彈性與即時互動。
* 驅動 AI 優化內容管理，提供智能建議、SEO 優化與自動化處理。
* 整合智慧電商功能，實現個性化推薦與使用者體驗。

## 🤖 Gemini CLI 整合支援

**環境準備**：

* Node.js 18 以上版本（建議使用 LTS），可透過 nvm 管理多版本。

**CLI 安裝**：

```bash
npm install --save-dev @google/gemini-cli
```

**金鑰設定**：
在專案根目錄 `.env` 裡加入：

```env
GEMINI_API_KEY=your_api_key_here
```

CLI 執行時會自動讀取，不需額外 `export`。

## 🚀 使用方式

* **互動模式**：

  ```bash
  npx @google/gemini-cli
  ```
* **Script 快捷**（加入 `package.json`）：

  ```jsonc
  "scripts": {
    "gemini": "npx @google/gemini-cli"
  }
  ```

## 🚀 快速開始

**需求環境**：

* PHP 8.3+
* Node.js 18+
* MySQL 8.0+
* Redis
* Windows 使用者建議透過 WSL2

**安裝步驟**：

```bash
git clone https://github.com/chunghsien/pinpin-aura.git
cd pinpin-aura

# Python 套件（HTML 樣板格式化）
sudo apt install python3-pip python3-venv
make python-setup

# 後端安裝與啟動
composer install
php artisan sail:install --devcontainer
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
sail up -d

# 環境變數
cp .env.example .env
sail artisan key:generate
vim .env

# 遷移與種子資料
sail artisan migrate --seed

# 安裝主題套件
sail artisan theme:install --org=pinpin --name=themes-lezada
sail artisan theme:install --org=pinpin --name=core-ui-admin

# 本地開發
sail composer dev
```

## 📃 授權

MIT License

---

*靈感來源：對兩位女兒的愛與期許，「pinpin」源自她們名字中共同的「品」字，願 AI 與用心結合，創造更多美好可能。*
