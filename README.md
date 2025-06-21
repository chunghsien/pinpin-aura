# pinpinAura

pinpinAura 是一個基於 Laravel 與 React 的智慧型 CMS 與電商整合平台，結合最新的人工智慧（AI）技術，提供直覺且高效的內容管理與電子商務解決方案。

## 🛠️ 專案特色

- **Laravel 後端架構**：穩定、安全、可擴展。
- **React 前端框架**：高效能、彈性、即時互動。
- **AI 驅動內容管理**：智能內容建議、SEO 優化、自動化處理。
- **智慧電商整合**：智慧推薦系統、個性化使用者體驗。

## 🚀 快速開始

### 需求環境

- PHP 8.3+
- Node.js 20+
- MySQL 8.0+
- Redis
- Windows系統強烈建議需要在wsl2下開發

### 安裝步驟

```bash
git clone https://github.com/chunghsien/pinpin-aura.git 
cd pinpin-aura
sudo apt install python3-pip python3-venv

# 建立虛擬環境（格式化 HTML 樣板用）
make python-setup
# 若需手動方式：
# python3 -m venv scripts/venv
# source scripts/venv/bin/activate
# pip install beautifulsoup4 lxml
# sudo apt-get install libxml2-dev libxslt1-dev python3-dev
# deactivate
composer install
php artisan sail:install
php artisan sail:install --devcontainer
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
sail up -d

cp .env.example .env
sail artisan key:generate

# 設定資料庫連線
vim .env

# 資料庫遷移
sail artisan migrate --seed

# 安裝前後端主題(packages)
sail artisan theme:install --org=pinpin --name=themes-lezada
sail artisan theme:install --org=pinpin --name=core-ui-admin

# 啟動專案
sail composer dev

```

### 安裝步驟說明

#### 基礎環境設定
- **`git clone`**：下載專案原始碼到本地
- **`sudo apt install python3-pip python3-venv`**：安裝 Python 套件管理器和虛擬環境工具，用於 HTML 樣板格式化
- **`make python-setup`**：建立 Python 虛擬環境並安裝 beautifulsoup4 和 lxml，用於自動格式化 HTML 樣板

#### Laravel 環境設定
- **`composer install`**：安裝 PHP 依賴套件，包括 Laravel 框架和相關套件
- **`php artisan sail:install`**：安裝 Laravel Sail（Docker 開發環境）
- **`php artisan sail:install --devcontainer`**：設定 VS Code Dev Container 配置，提供一致的開發環境
- **`alias sail`**：建立 sail 指令別名，簡化 Docker 指令操作
- **`sail up -d`**：啟動 Docker 容器（資料庫、Redis 等服務）

#### 應用程式設定
- **`cp .env.example .env`**：複製環境變數範本檔案
- **`sail artisan key:generate`**：產生 Laravel 應用程式金鑰，用於加密和安全性
- **`vim .env`**：編輯環境變數，設定資料庫連線等配置
- **`sail artisan migrate --seed`**：執行資料庫遷移並填入初始資料，建立資料表結構和預設資料

#### 主題套件安裝
- **`sail artisan theme:install --org=pinpin --name=themes-lezada`**：
  - 安裝電商主題套件
  - 註冊 PHP 套件到 Laravel
  - 建立符號連結，讓 Vite 能處理前端資源
  - 註冊 Blade 組件和 Livewire 組件
  - 執行主題相關的資料庫 Seeder

- **`sail artisan theme:install --org=pinpin --name=core-ui-admin`**：
  - 安裝後台管理套件
  - 註冊 React 管理介面
  - 建立符號連結，讓 Vite 能處理 React 資源
  - 設定管理介面路由

#### 開發環境啟動
- **`sail composer dev`**：啟動完整的開發環境，包括：
  - Laravel 開發伺服器
  - Vite 前端建置工具
  - 檔案監控和熱重載
  - 佇列處理器
  - 日誌監控

### Makefile 快速指令

```bash
make python-setup   # 安裝 bs4+lxml 到 scripts/venv
make html-format    # 使用 scripts/format_bs4.py 格式化樣板（需修改 input）
make python-clean   # 移除虛擬環境
```

## 📦 Packages 套件說明

### themes-lezada
- **用途**：電商主題套件，提供完整的電商前端介面
- **類型**：PHP 套件 + 前端資源
- **安裝**：`sail artisan theme:install --org=pinpin --name=themes-lezada`
- **功能**：包含 Livewire 組件、Blade 模板、SCSS 樣式、TypeScript 程式碼

### core-ui-admin
- **用途**：後台管理系統，提供 React 基礎的管理介面
- **類型**：PHP 套件 + React 前端
- **安裝**：`sail artisan theme:install --org=pinpin --name=core-ui-admin`
- **功能**：React 組件、TypeScript 程式碼、管理介面路由

### 套件架構
```
packages/pinpin/
├── themes-lezada/          # 電商主題套件
│   ├── src/               # PHP 後端邏輯
│   ├── resources/         # 前端資源 (ts, sass, views)
│   ├── public/            # 靜態資源
│   └── composer.json      # 套件定義
└── core-ui-admin/         # 後台管理套件
    ├── src/               # PHP 後端邏輯
    ├── resources/         # 前端資源 (ts)
    └── composer.json      # 套件定義
```

### 符號連結機制
- 前端資源透過符號連結映射到 `resources/ts/themes/`
- Vite 可直接處理 `resources/ts/themes/` 下的檔案
- 保持套件完整性同時簡化前端開發流程

## 🌐 技術棧 Technology Stack

- Laravel 12
- React
- TypeScript
- MySQL
- Redis
- Docker


## 📦 未來規劃

- 強化 AI 智能推薦功能
- 實作個性化推播通知
- 完善系統監控與效能優化

## 🧑‍💻 貢獻專案

歡迎提交 Pull Requests 或 Issue，協助我們讓 pinpinAura 更加強大！

## 📃 授權

MIT License

---
建立本專案的靈感來自我對兩個女兒的愛與期許，「pinpin」源自兩位女兒名字中共同擁有的「品」字，願這份用心與 AI 的結合，能創造更多的可能性與美好。
