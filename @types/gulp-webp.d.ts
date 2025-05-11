//@types/gulp-webp.d.ts
declare module "gulp-webp" {
    import { Transform } from "stream";

    interface WebPOptions {
        quality?: number;
        preset?: "default" | "photo" | "picture" | "drawing" | "icon" | "text";
        method?: number;
    }

    export default function webp(options?: WebPOptions): Transform;
}
