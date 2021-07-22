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
}

export class MediaData {
  id!: number
  town!: number
  title!: string
  timestamp!: string
  latitude!: number
  longitude!: number
  type!: 'twitter' | 'reddit' | 'youtube' | 'iframe'
  format!: 'image' | 'video'
  data!: string
}

const apiUrl = 'http://hochwasser.digitalbread.de/api'

export async function getTowns (): Promise<TownData[]> {
  return fetch(apiUrl + '/towns').then(response => response.json())
}

export async function getTown (name: string): Promise<TownData> {
  return fetch(apiUrl + '/town/' + encodeURIComponent(name)).then(response => response.json())
}

export async function getMedia (id: number): Promise<MediaData[]> {
  return fetch(apiUrl + '/media/' + id).then(response => response.json())
}
