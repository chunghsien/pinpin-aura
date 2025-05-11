# Makefile for pinpinAura - header formatter support

# 建立 Python 虛擬環境並安裝 bs4 + lxml
python-setup:
	cd scripts && python3 -m venv venv && \
	source venv/bin/activate && \
	pip install --upgrade pip && \
	pip install beautifulsoup4 lxml

# 格式化 HTML 樣板範例（請自行修改 input 路徑）
html-format:
	python3 scripts/format_bs4.py resources/views/sample.html > /tmp/formatted.html

# 清除虛擬環境
python-clean:
	rm -rf scripts/venv

# 顯示說明
help:
	@echo "可用指令："
	@echo "  make python-setup   - 安裝 bs4+lxml 到虛擬環境"
	@echo "  make html-format    - 執行格式化（需修改 input）"
	@echo "  make python-clean   - 刪除虛擬環境"
	@echo "  make help           - 顯示這個說明"
