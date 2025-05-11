declare module "gulp-avif" {
    import { Transform } from "stream";

    export interface AvifOptions {
        quality?: number; // 壓縮品質 0–100（預設約 50）
        lossless?: boolean; // 是否啟用無損壓縮
        speed?: number; // 壓縮速度（0=慢但壓縮率高，9=快但壓縮差）
    }

    const avif: (options?: AvifOptions) => Transform;

    export default avif;
}
