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

const apiUrl = 'https://hochwasser-ahrtal-2021.de/api'

export async function getTowns (): Promise<TownData[]> {
  return fetch(apiUrl + '/towns').then(response => response.json())
}

export async function getTown (name: string): Promise<TownData> {
  return fetch(apiUrl + '/town/' + encodeURIComponent(name)).then(response => response.json())
}

export async function getMedia (id: number): Promise<MediaData[]> {
  return fetch(apiUrl + '/media/' + id).then(response => response.json())
}

export async function postContact (contact: ContactData | {[k: string]: FormDataEntryValue}): Promise<Response> {
  return fetch(apiUrl + '/contact', {
    method: 'POST',
    mode: 'cors',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(contact)
  })
}

export async function postPersonalMedia (complaint: PersonalMediaData | {[k: string]: FormDataEntryValue}): Promise<Response> {
  return fetch(apiUrl + '/personal', {
    method: 'POST',
    mode: 'cors',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(complaint)
  })
}

export async function postComplaint (complaint: ComplaintData | {[k: string]: FormDataEntryValue}): Promise<Response> {
  return fetch(apiUrl + '/complaint', {
    method: 'POST',
    mode: 'cors',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(complaint)
  })
}
