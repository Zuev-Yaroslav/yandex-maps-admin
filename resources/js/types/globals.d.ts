import type { AxiosInstance } from 'axios';
import type { route as routeFn } from 'ziggy-js';
import type { AppPageProps } from './index';

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        readonly VITE_YMAP_API_KEY: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}

declare global {
    var route: typeof routeFn;
}

declare global {
    interface Window {
        axios: AxiosInstance;
    }
    // Это позволит использовать просто axios.post() без window.
    const axios: AxiosInstance;
}

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        route: (
            name?: RouteName,
            params?: RouteParams<RouteName>,
            absolute?: boolean,
            config?: Config,
        ) => any;
    }
}

declare module 'vue' {
    interface ComponentCustomProperties {
        $inertia: typeof Router;
        $page: Page;
        $headManager: ReturnType<typeof createHeadManager>;
    }
}
