import levenshtein from 'js-levenshtein'
import NodeCache from 'node-cache'

export class TownData {
  id!: number
  name!: string
  route!: string
  x!: number
  y!: number
  latitude!: number
  longitude!: number
  zoom!: number
  label!: 'left' | 'right'
  prev!: string
  next!: string
}

export class MediaData {
  id!: number
  town!: number
  title!: string
  timestamp!: string
  latitude!: number
  longitude!: number
  type!: 'twitter' | 'reddit' | 'youtube' | 'iframe' | 'link' | 'img'
  format!: 'image' | 'video'
  data!: string
}

export class ContactData {
  name!: string
  email!: string
  request!: string
  latitude!: number
  longitude!: number
  copyright!: string
  gdpr!: string
}

export class PersonalMediaData {
  name!: string
  email!: string
  request!: string
}

export class ComplaintData {
  name!: string
  email!: string
  request!: string
  gdpr!: string
}

export class CorrectionData {
  media!: number
  name!: string
  latitude!: number
  longitude!: number
}

export class MediaFilter {
  format?: ('image' | 'video')[]
  type?: ('twitter' | 'reddit' | 'youtube' | 'iframe' | 'link' | 'img')[]
  before?: Date
  since?: Date
  fuzzyName?: string

  private listener: ((m: MediaFilter) => void)[] = []

  public addListener (f: (m: MediaFilter) => void): void {
    console.log('New Event listener', f)
    this.listener.push(f)
  }

  public triggerEvent (): void {
    for (const f of this.listener) {
      f(this)
    }
  }
}

const apiUrl = process.env.VUE_APP_API_URL
const cache = new NodeCache({ stdTTL: 900 })

export async function getTowns (): Promise<TownData[]> {
  const url = apiUrl + '/towns'
  const cached = cache.get(url) as TownData[]
  if (cached) {
    return new Promise(resolve => resolve(cached))
  } else {
    return fetch(url).then(response => response.json()).then(json => {
      cache.set(url, json)
      return json
    })
  }
}

export async function getTown (name: string): Promise<TownData> {
  const url = apiUrl + '/town/' + encodeURIComponent(name)
  const cached = cache.get(url) as TownData
  if (cached) {
    return new Promise(resolve => resolve(cached))
  } else {
    return fetch(url).then(response => response.json()).then(json => {
      cache.set(url, json)
      return json
    })
  }
}

async function filterMedia (media: Promise<MediaData[]>, filter: MediaFilter): Promise<MediaData[]> {
  return media.then(mediaDatas => {
    const filtered: MediaData[] = []

    for (const mediaData of mediaDatas) {
      if (filter.format !== undefined && filter.format.indexOf(mediaData.format) < 0) continue
      if (filter.type !== undefined && filter.type.indexOf(mediaData.type) < 0) continue
      const date = new Date(mediaData.timestamp)
      if (filter.before !== undefined && date.getTime() > filter.before.getTime()) continue
      if (filter.since !== undefined && date.getTime() < filter.since.getTime()) continue
      if (filter.fuzzyName !== undefined && levenshtein(filter.fuzzyName.toLocaleLowerCase('de-DE'), mediaData.title.toLocaleLowerCase('de-DE')) > 10) continue
      filtered.push(mediaData)
    }
    return filtered
  })
}

export async function getMedia (id: number, filter?: MediaFilter): Promise<MediaData[]> {
  const url = apiUrl + '/media/' + id
  const cached = cache.get(url) as MediaData[]

  if (filter) {
    if (cached) {
      return filterMedia(new Promise(resolve => resolve(cached)), filter)
    } else {
      return filterMedia(fetch(url).then(response => response.json()).then(json => {
        cache.set(url, json)
        return json
      }), filter)
    }
  } else {
    if (cached) {
      return new Promise(resolve => resolve(cached))
    } else {
      return fetch(url).then(response => response.json()).then(json => {
        cache.set(url, json)
        return json
      })
    }
  }
}

export async function postContact (contact: ContactData | { [k: string]: FormDataEntryValue }): Promise<Response> {
  return fetch(apiUrl + '/contact', {
    method: 'POST',
    mode: 'cors',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(contact)
  })
}

export async function postPersonalMedia (personalMedia: PersonalMediaData | { [k: string]: FormDataEntryValue }): Promise<Response> {
  return fetch(apiUrl + '/personal', {
    method: 'POST',
    mode: 'cors',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(personalMedia)
  })
}

export async function postComplaint (complaint: ComplaintData | { [k: string]: FormDataEntryValue }): Promise<Response> {
  return fetch(apiUrl + '/complaint', {
    method: 'POST',
    mode: 'cors',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(complaint)
  })
}

export async function postCorrection (correction: CorrectionData | { [k: string]: FormDataEntryValue }): Promise<Response> {
  return fetch(apiUrl + '/correction', {
    method: 'POST',
    mode: 'cors',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(correction)
  })
}
