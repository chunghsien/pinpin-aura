
# Nginx 反向代理 + Docker Sail 對接說明

## 📌 1. 建立共用 Proxy Network

```bash
docker network create nginx_proxy
```

---

## 📌 2. 啟動 Nginx Proxy 容器

### 方法 1：使用 `jwilder/nginx-proxy`

```bash
docker run -d --name nginx-proxy \
    --network nginx_proxy \
    -p 80:80 -p 443:443 \
    -v /var/run/docker.sock:/tmp/docker.sock:ro \
    -v /path/to/custom/nginx/conf.d:/etc/nginx/conf.d \
    jwilder/nginx-proxy
```

### 方法 2：使用 Nginx Proxy Manager（圖形介面管理）

```bash
docker run -d --name nginx-proxy-manager \
    --network nginx_proxy \
    -p 80:80 -p 81:81 -p 443:443 \
    -v /path/to/data:/data \
    -v /path/to/letsencrypt:/etc/letsencrypt \
    jc21/nginx-proxy-manager
```

> Port 81 是管理介面，預設帳號 admin@example.com / 密碼 changeme  

---

## 📌 3. 專案 `docker-compose.yml` 修改範例

```yaml
networks:
  nginx_proxy:
    external: true

services:
  laravel.test:
    networks:
      - nginx_proxy
    environment:
      VIRTUAL_HOST: pinpin-aura.localhost
      VIRTUAL_PORT: 80
```

---

## 📌 4. 修改 Hosts 檔案（本機開發使用）

在 `C:\Windows\System32\drivers\etc\hosts` 增加以下內容：

```
127.0.0.1 pinpin-aura.localhost
```

---

## 📌 5. 重啟服務

```bash
docker-compose down
docker-compose up -d
```

---

## ✅ **完成！現在可以直接透過 `http://pinpin-aura.localhost` 訪問專案。**


---

# 📚 開發環境 HTTPS 憑證解決方案

## 📌 1. 使用自簽憑證（Self-Signed Certificate）

### ✅ 建立自簽憑證

```bash
mkdir -p ./docker/certs
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout ./docker/certs/selfsigned.key \
  -out ./docker/certs/selfsigned.crt \
  -subj "/CN=pinpin-aura.localhost/O=Local Dev/C=TW"
```

### ✅ `docker-compose.yml` 掛載憑證範例

```yaml
services:
  nginx-proxy:
    volumes:
      - ./docker/certs:/etc/nginx/certs
```

> 瀏覽器會顯示「不安全憑證」警告，手動信任後即可使用。

---

## 📌 2. 使用 `mkcert` 快速產生受信任的本機憑證

### ✅ 安裝 `mkcert`

```bash
# WSL / Ubuntu
sudo apt install libnss3-tools
brew install mkcert  # 如果使用 Homebrew

# 安裝本機信任憑證機構
mkcert -install
```

### ✅ 產生本機受信任的憑證

```bash
mkcert pinpin-aura.localhost
```

會產生以下檔案：

```
pinpin-aura.localhost.pem
pinpin-aura.localhost-key.pem
```

### ✅ 將憑證掛載到 Nginx Proxy 容器

```yaml
services:
  nginx-proxy:
    volumes:
      - /完整憑證路徑/pinpin-aura.localhost.pem:/etc/nginx/certs/pinpin-aura.localhost.crt
      - /完整憑證路徑/pinpin-aura.localhost-key.pem:/etc/nginx/certs/pinpin-aura.localhost.key
```

---

## ✅ **完成！本機環境也能直接使用 HTTPS 開發。**

> 使用 `mkcert` 不會產生憑證警告，是最推薦的本機開發 HTTPS 解決方案。
