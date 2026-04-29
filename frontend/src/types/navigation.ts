import type { IconDefinition } from '@fortawesome/fontawesome-svg-core'

export interface NavItem {
  label: string
  icon: IconDefinition
  to: string
  name?: string
  resource?: string
}

export interface NavGroup {
  label: string
  icon: IconDefinition
  items: NavItem[]
  permission?: string
}