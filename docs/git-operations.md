# Git 操作備忘錄

- 紀錄平時用到的 Git 指令與情境，幫助自己回想。

---

## 📌 如何從 clone 下來的專案開分支

🧭 基本流程

```bash
# 1. 先切換到專案目錄
cd 你剛剛 clone 下來的資料夾名稱

# 2. 確認目前所在的分支
git branch

# 3. 拉取最新的遠端分支（可選但建議）
git fetch origin

# 4. 建立並切換到新分支（例如 new-feature）
git checkout -b new-feature

# 或是如果你要從特定遠端分支出發：
git checkout -b new-feature origin/main
```

📝 補充說明

| 指令                                       | 功能說明               |
|------------------------------------------|----------------------|
| `git branch`                             | 查看本地有哪些分支         |
| `git checkout -b <branch-name>`          | 建立並切換到新分支         |
| `git checkout -b <新分支> origin/<遠端分支>` | 從遠端某個分支建立新分支      |
| `git push -u origin <branch-name>`       | 將新分支推送到遠端，並設定追蹤  |

---

## 📌 我要怎樣知道我目前在那個 Git 分支裡

- 最直接的方式：
```bash
git branch
```
- 當前分支會在前面標記 `*`。

- 只顯示當前分支名稱：
```bash
git rev-parse --abbrev-ref HEAD
```

---

## 📌 若使用過 `git add <file>` 或 `git add .`，已將檔案變更放進「暫存區」（staged），但現在想只提交特定檔案時...

- **情境：** 現在只想提交 `.gitignore`，其他先暫時不提交：

```bash
git reset         # 把所有已 staged 檔案取消暫存，但不改動檔案內容
git add .gitignore
git commit -m "chore: 更新 .gitignore 規則"
```

- **如果只想取消暫存特定檔案：**
```bash
git reset HEAD <file>  # 只取消暫存單一檔案
```

📝 總結  
- `git reset` → 把「已經準備提交」的狀態取消掉。  
- 不會刪檔案、不會改內容，只是「不準備提交」了。  
- 常用來避免一不小心 `git add .` 把太多東西加進去了。

---
