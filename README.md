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



# 啟動專案
sail composer dev

```

### Makefile 快速指令

```bash
make python-setup   # 安裝 bs4+lxml 到 scripts/venv
make html-format    # 使用 scripts/format_bs4.py 格式化樣板（需修改 input）
make python-clean   # 移除虛擬環境
```

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
