# 📚 GitHub Issue 基礎使用

## ✅ 1. 開 Issue：列出待辦任務

1. 進入 GitHub Repo → 點選 `Issues` → `New Issue`。
2. 填入標題與描述，可加標籤與指派負責人。

### ✍️ 範例：

**Title:** `[Feature] Admin Panel 語系切換功能`

**Body:**

```markdown
## 需求說明
- [ ] 頁面切換後保持語系狀態
- [ ] 語系預設值讀取環境變數 APP_LOCALE

## 優先度
- ⬜ Medium

## 負責人
- @chunghsien
```
---

## ✅ 2. Commit 時關聯 Issue

```bash
git commit -m "feat: 完成語系切換邏輯 #12"
```

- `#12` 是 Issue 編號，GitHub 會自動在 Issue 上記錄這筆 Commit。

---

## ✅ 3. 自動關閉 Issue

```bash
git commit -m "fix: 修正語系錯誤 Close #12"
```

- 使用關鍵字：`close`, `closes`, `closed`, `fixes`, `resolves`  
- PR 合併時 Issue 會自動關閉。

---

## ✅ 4. Pull Request 時關聯 Issue

在 PR 描述中加入：

```markdown
Closes #12
```

---

## ✅ 5. 標籤分類建議

| 標籤名稱        | 說明       | 顏色建議  |
|-----------------|------------|-----------|
| `bug`           | 錯誤修正    | #d73a4a   |
| `feature`       | 新功能開發  | #a2eeef   |
| `enhancement`   | 功能優化    | #84b6eb   |
| `refactor`      | 重構        | #fbca04   |
| `documentation` | 文件更新    | #0e8a16   |
| `performance`   | 效能優化    | #ff8c00   |
| `help wanted`   | 需要協助    | #008672   |
| `question`      | 問題或討論  | #cc317c   |
| `urgent`        | 高優先任務  | #b60205   |

---

## 📌 簡單工作流程

1. 📋 建立 Issue → 分配負責人  
2. 🛠 開發中 → Commit 時關聯 Issue  
3. ✅ 功能完成 → PR 合併，自動關閉 Issue  
4. 📚 留下記錄 → 隨時查詢任務歷史與程式變更