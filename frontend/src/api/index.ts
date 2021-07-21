export class TownData {
  id!: number
  name!: string
  route!: string
  x!: number
  y!: number
}

export class MediaData {
  id!: number
  town!: number
  title!: string
  timestamp!: string
  latitude!: number
  longitude!: number
  type!: 'twitter' | 'reddit' | 'youtube' | 'iframe'
  data!: string
}

const apiUrl = 'http://hochwasser.local/api'

export async function getTowns (): Promise<TownData[]> {
  return fetch(apiUrl + '/towns').then(response => response.json())
}

export async function getTown (id: number): Promise<TownData> {
  return fetch(apiUrl + '/town/' + id).then(response => response.json())
}

export async function getMedia (id: number): Promise<MediaData[]> {
  return fetch(apiUrl + '/media/' + id).then(response => response.json())
}
