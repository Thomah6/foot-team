/**
 * Service pour les utilitaires de calendrier et dates
 */

/**
 * Obtenir les jours de présences du mois
 * @param {Date} date - La date du mois
 * @returns {Array} Tableau des dates formatées du mois
 */
export function getMonthDates(date = new Date()) {
  const month = date.getMonth()
  const year = date.getFullYear()
  const firstDay = new Date(year, month, 1)
  const lastDay = new Date(year, month + 1, 0)

  const dates = []
  for (let d = firstDay; d <= lastDay; d.setDate(d.getDate() + 1)) {
    dates.push(new Date(d).toISOString().split('T')[0])
  }

  return dates
}

/**
 * Formater une date en français
 * @param {string} dateStr - La date au format ISO (YYYY-MM-DD)
 * @returns {string} Date formatée
 */
export function formatDateFR(dateStr) {
  const date = new Date(dateStr + 'T00:00:00')
  return date.toLocaleDateString('fr-FR', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}

/**
 * Obtenir le jour de la semaine abrégé et le jour du mois
 * @param {string} dateStr - La date au format ISO
 * @returns {string} Format: "Lun 15"
 */
export function getShortDateFormat(dateStr) {
  const date = new Date(dateStr + 'T00:00:00')
  const dayNames = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam']
  const dayName = dayNames[date.getDay()]
  const dayNum = date.getDate()
  const month = (date.getMonth() + 1).toString().padStart(2, '0')

  return `${dayName} ${dayNum}/${month}`
}

/**
 * Obtenir le mois et l'année formatés
 * @param {Date} date - La date
 * @returns {string} Format: "Juillet 2024"
 */
export function formatMonthYear(date) {
  const formatter = new Intl.DateTimeFormat('fr-FR', {
    month: 'long',
    year: 'numeric',
  })
  const formatted = formatter.format(date)
  return formatted.charAt(0).toUpperCase() + formatted.slice(1)
}

/**
 * Ajouter des jours à une date
 * @param {Date} date - La date de base
 * @param {number} days - Nombre de jours à ajouter
 * @returns {Date} Nouvelle date
 */
export function addDays(date, days) {
  const result = new Date(date)
  result.setDate(result.getDate() + days)
  return result
}

/**
 * Obtenir le premier jour du mois
 * @param {Date} date - Une date du mois
 * @returns {Date} Premier jour du mois
 */
export function getFirstDayOfMonth(date) {
  return new Date(date.getFullYear(), date.getMonth(), 1)
}

/**
 * Obtenir le dernier jour du mois
 * @param {Date} date - Une date du mois
 * @returns {Date} Dernier jour du mois
 */
export function getLastDayOfMonth(date) {
  return new Date(date.getFullYear(), date.getMonth() + 1, 0)
}

/**
 * Vérifier si deux dates sont le même jour
 * @param {Date} date1 - Première date
 * @param {Date} date2 - Deuxième date
 * @returns {boolean}
 */
export function isSameDay(date1, date2) {
  return (
    date1.getFullYear() === date2.getFullYear() &&
    date1.getMonth() === date2.getMonth() &&
    date1.getDate() === date2.getDate()
  )
}

/**
 * Calculer le pourcentage de présence
 * @param {number} present - Nombre de présences
 * @param {number} total - Nombre total de jours
 * @returns {number} Pourcentage arrondi
 */
export function calculatePresencePercentage(present, total) {
  if (total === 0) return 0
  return Math.round((present / total) * 100)
}

/**
 * Formater un statut de présence
 * @param {boolean} present - Présent ou absent
 * @param {boolean} validated - Validé ou non
 * @returns {object} Objet avec icône et couleur
 */
export function getPresenceStatus(present, validated) {
  if (!present) {
    return {
      icon: 'cancel',
      color: 'text-red-500',
      label: 'Absent',
      bgColor: 'bg-red-50',
    }
  }

  if (present && validated) {
    return {
      icon: 'check_circle',
      color: 'text-green-500',
      label: 'Présent validé',
      bgColor: 'bg-green-50',
    }
  }

  return {
    icon: 'schedule',
    color: 'text-yellow-500',
    label: 'En attente de validation',
    bgColor: 'bg-yellow-50',
  }
}

/**
 * Comparer deux mois
 * @param {Date} date1 - Première date
 * @param {Date} date2 - Deuxième date
 * @returns {number} -1, 0 ou 1
 */
export function compareMonths(date1, date2) {
  if (date1.getFullYear() !== date2.getFullYear()) {
    return date1.getFullYear() - date2.getFullYear()
  }
  return date1.getMonth() - date2.getMonth()
}

/**
 * Obtenir la semaine de présence d'un mois
 * Retourne les dates de présence par semaine
 * @param {Array} dates - Tableau de dates (YYYY-MM-DD)
 * @returns {Array} Tableau de semaines
 */
export function groupDatesByWeek(dates) {
  const weeks = []
  let currentWeek = []

  dates.forEach((dateStr) => {
    const date = new Date(dateStr + 'T00:00:00')
    if (currentWeek.length > 0 && date.getDay() === 1 && currentWeek.length > 0) {
      weeks.push([...currentWeek])
      currentWeek = []
    }
    currentWeek.push(dateStr)
  })

  if (currentWeek.length > 0) {
    weeks.push(currentWeek)
  }

  return weeks
}

/**
 * Obtenir les jours ouvrables du mois (du lundi au vendredi)
 * @param {Date} date - Une date du mois
 * @returns {Array} Tableau des dates formatées
 */
export function getWorkingDaysOfMonth(date = new Date()) {
  const month = date.getMonth()
  const year = date.getFullYear()
  const firstDay = new Date(year, month, 1)
  const lastDay = new Date(year, month + 1, 0)

  const workingDays = []
  for (let d = firstDay; d <= lastDay; d.setDate(d.getDate() + 1)) {
    const dayOfWeek = d.getDay()
    // 1 = lundi, 5 = vendredi
    if (dayOfWeek >= 1 && dayOfWeek <= 5) {
      workingDays.push(new Date(d).toISOString().split('T')[0])
    }
  }

  return workingDays
}

/**
 * Formater une période de dates
 * @param {string} startDate - Date de début
 * @param {string} endDate - Date de fin
 * @returns {string} Format: "15 juillet - 31 juillet 2024"
 */
export function formatDateRange(startDate, endDate) {
  const start = new Date(startDate + 'T00:00:00')
  const end = new Date(endDate + 'T00:00:00')

  const startFormatted = start.toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
  })

  const endFormatted = end.toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  })

  return `${startFormatted} - ${endFormatted}`
}
