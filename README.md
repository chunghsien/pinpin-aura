# pinpinAura

pinpinAura 是一個基於 Laravel 與 React 的智慧型 CMS 與電商整合平台，結合 AI 技術提供高效內容管理與個性化購物體驗。

## 🛠️ 專案特色

-   採用 Laravel 後端架構，確保穩定、安全與可擴展。
-   採用 React 前端框架，提供高效能、彈性與即時互動。
-   驅動 AI 優化內容管理，提供智能建議、SEO 優化與自動化處理。
-   整合智慧電商功能，實現個性化推薦與使用者體驗。

## 🤖 Gemini CLI 整合支援

**環境準備**：

-   Node.js 18 以上版本（建議使用 LTS），可透過 nvm 管理多版本。

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

-   **互動模式**：

    ```bash
    npx @google/gemini-cli
    ```

-   **Script 快捷**（加入 `package.json`）：

    ```jsonc
    "scripts": {
      "gemini": "npx @google/gemini-cli"
    }
    ```

## 🚀 快速開始

**需求環境**：

-   PHP 8.3+
-   Node.js 18+
-   MySQL 8.0+
-   Redis
-   Windows 使用者建議透過 WSL2

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

## 🔌 IDE 擴充套件安裝

專案提供了自動安裝 IDE 擴充套件的腳本，支援 Cursor 和 VS Code 兩種編輯器。腳本會安裝開發所需的所有擴充套件，包括：

-   Laravel 開發支援
-   React/TypeScript 支援
-   Docker 整合
-   Git 工具
-   程式碼格式化
-   主題與介面優化

**使用方式**：

```bash
# 設定腳本執行權限
chmod +x install-extensions.sh

# 執行安裝腳本
./install-extensions.sh

# 根據提示選擇目標編輯器：
# 1) Cursor
# 2) VS Code
```

⚠️ **注意事項**：

-   請確保已安裝選擇的編輯器，且相關指令（`cursor` 或 `code`）已加入系統 PATH
-   Dev Containers 擴充套件需要手動安裝

## 📃 授權

MIT License

---

_靈感來源：對兩位女兒的愛與期許，「pinpin」源自她們名字中共同的「品」字，願 AI 與用心結合，創造更多美好可能。_
