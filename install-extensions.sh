#!/bin/bash

# 自訂 CLI 名稱選擇
echo "請選擇你要安裝的目標編輯器："
echo "1) Cursor"
echo "2) VS Code"
read -p "輸入數字 1 或 2 然後按 Enter: " choice

case "$choice" in
  1)
    CMD="cursor"
    ;;
  2)
    CMD="code"
    ;;
  *)
    echo "❌ 無效選項，請輸入 1 或 2。"
    exit 1
    ;;
esac

# 檢查是否安裝該 CLI
if ! command -v $CMD &> /dev/null; then
  echo "❌ 指令 $CMD 不存在，請確認是否已安裝並加入 PATH。"
  exit 1
fi

echo "✅ 將使用 $CMD 安裝以下套件..."

extensions=(
  amiralizadeh9480.laravel-extra-intellisense
  bierner.markdown-mermaid
  bmewburn.vscode-intelephense-client
  codingyu.laravel-goto-view
  docker.docker
  doonfrs.livewire-support
  dsznajder.es7-react-js-snippets
  eamodio.gitlens
  ecmel.vscode-html-css
  esbenp.prettier-vscode
  infeng.vscode-react-typescript
  jaspernorth.vscode-pigments
  junstyle.php-cs-fixer
  mikestead.dotenv
  mrchetan.goto-laravel-components
  ms-azuretools.vscode-containers
  ms-azuretools.vscode-docker
  onecentlin.laravel-blade
  onecentlin.laravel5-snippets
  shufo.vscode-blade-formatter
  whatwedo.twig
  zhuangtongfa.material-theme
)

for ext in "${extensions[@]}"; do
  $CMD --install-extension "$ext"
done

echo "🎉 所有擴充套件已安裝完成到 $CMD！"
echo "⚠️ 如果使用 vscode 請自行安裝 vscode Dev Containers 擴充套件"
echo "⚠️ 如果使用 cursor 請自行安裝 cursor Dev Containers 擴充套件"
