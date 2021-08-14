/* eslint-disable */
declare module '*.vue' {
  import type {DefineComponent} from 'vue'
  const component: DefineComponent<{}, {}, any>
  export default component
}

declare module '@dvuckovic/vue3-bootstrap-icons'
declare module '@vue-leaflet/vue-leaflet' {
  import L from 'leaflet'

  export class LMap {
    leafletObject: L.Map
  }

  export class LMarker {
  }

  export class LPopup {
  }

  export class LTileLayer {
  }
}
declare module 'js-levenshtein' {
  const levenshtein: (s1: string, s2: string) => number
  export default levenshtein
}

// https://github.com/microsoft/TypeScript/issues/35865
declare namespace Intl {
  interface DateTimeFormatOptions {
    dateStyle?: 'full' | 'long' | 'medium' | 'short';
    timeStyle?: 'full' | 'long' | 'medium' | 'short';
  }
}

