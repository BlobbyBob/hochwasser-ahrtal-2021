export class TownData {
  id!: number
  name!: string
  route!: string
  x!: number
  y!: number
}

export function getTowns (): TownData[] {
  const schuld = new TownData()
  schuld.id = 1
  schuld.name = 'Schuld'
  schuld.route = 'schuld'
  schuld.x = 780
  schuld.y = 630

  const insul = new TownData()
  insul.id = 2
  insul.name = 'Insul'
  insul.route = 'insul'
  insul.x = 855
  insul.y = 650

  return [schuld, insul]
}
