# 實作範例

1️⃣ vite.config.js 多入口設定

```js
import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
    build: {
        outDir: 'public/assets',
        rollupOptions: {
            input: {
                main: path.resolve(__dirname, 'resources/ts/main.ts'),          // 共用 JS
                home: path.resolve(__dirname, 'resources/ts/pages/home.ts'),    // 首頁專用
                product: path.resolve(__dirname, 'resources/ts/pages/product.ts') // 商品頁專用
            },
        },
    },
});
```
2️⃣ 檔案結構建議

```bash
resources/
└── ts/
    ├── main.ts                  # 共用腳本
    └── pages/
        ├── home.ts              # 首頁 JS
        └── product.ts           # 商品頁 JS
```

3️⃣ Blade 模板載入方式

```blade
<!-- 共用 JS -->
<script type="module" src="{{ asset('assets/main.js') }}"></script>

<!-- 特定頁面 JS -->
@if (request()->is('/'))
    <script type="module" src="{{ asset('assets/home.js') }}"></script>
@elseif (request()->is('product/*'))
    <script type="module" src="{{ asset('assets/product.js') }}"></script>
@endif
```

- 你也可以用 Controller 傳變數來控制載入哪些 JS。


## 🚀 優點

✅ 減少無用 JS 載入，提高前台載入速度。

✅ TypeScript 管理模組，程式碼好維護。

✅ 可以慢慢遷移，逐步引入 Vite，不會一下子打掉重練。

