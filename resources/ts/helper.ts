import fs from "fs";
import path from "path";
import mysql, { Connection } from "mysql2/promise";
import dotenv from "dotenv";
import { fileURLToPath } from "url";

// 模擬 __dirname（TS + ESM）
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// 載入 .env（預設假設在根目錄）
const envPath = path.resolve(__dirname, "../../.env");
dotenv.config({ path: envPath });

// MySQL 設定
const dbConfig = {
  host: process.env.DB_HOST || "localhost",
  user: process.env.DB_USERNAME || "root",
  password: process.env.DB_PASSWORD || "",
  database: process.env.DB_DATABASE || "",
  port: Number(process.env.DB_PORT) || 3306,
};

// 檢查檔案是否存在且為 file
export function isFile(filePath: string): boolean {
  try {
    return fs.existsSync(filePath) && fs.statSync(filePath).isFile();
  } catch (err) {
    console.warn(err);
    return false;
  }
}

// 資料表查詢（根據 installed_themes）
export async function fetchDataFromDB(): Promise<any[]> {
  let connection: Connection | null = null;
  try {
    connection = await mysql.createConnection(dbConfig);
    const [rows] = await connection.execute(
      "SELECT * FROM installed_themes WHERE is_active = 1"
    );
    return rows as any[];
  } finally {
    if (connection) await connection.end();
  }
}
