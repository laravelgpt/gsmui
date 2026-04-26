
import { ref, computed, watch } from 'vue'

/**
 * Composable for DataTable functionality
 * Provides reactive state and methods for table operations
 */
export function useDataTable(initialData = [], config = {}) {
  // State
  const data = ref([...initialData])
  const filters = ref({})
  const sortConfig = ref({ key: null, direction: 'asc' })
  const selectedRows = ref([])
  const currentPage = ref(1)
  const itemsPerPage = ref(config.itemsPerPage || 10)
  const loading = ref(false)
  const error = ref(null)

  // Computed
  const processedData = computed(() => {
    let processed = [...data.value]

    // Apply filters
    Object.entries(filters.value).forEach(([key, value]) => {
      if (value) {
        processed = processed.filter(item => 
          String(item[key] || '').toLowerCase().includes(value.toLowerCase())
        )
      }
    })

    // Apply sorting
    if (sortConfig.value.key) {
      processed.sort((a, b) => {
        const aValue = a[sortConfig.value.key]
        const bValue = b[sortConfig.value.key]
        
        if (aValue < bValue) return sortConfig.value.direction === 'asc' ? -1 : 1
        if (aValue > bValue) return sortConfig.value.direction === 'asc' ? 1 : -1
        return 0
      })
    }

    return processed
  })

  const paginatedData = computed(() => {
    const startIndex = (currentPage.value - 1) * itemsPerPage.value
    return processedData.value.slice(startIndex, startIndex + itemsPerPage.value)
  })

  const totalPages = computed(() => 
    Math.ceil(processedData.value.length / itemsPerPage.value)
  )

  const totalItems = computed(() => data.value.length)

  const filteredCount = computed(() => processedData.value.length)

  const isSelectedAll = computed(() => 
    selectedRows.value.length === paginatedData.value.length && paginatedData.value.length > 0
  )

  const isIndeterminate = computed(() => 
    selectedRows.value.length > 0 && selectedRows.value.length < paginatedData.value.length
  )

  // Methods
  const sortBy = (key) => {
    if (sortConfig.value.key === key) {
      sortConfig.value.direction = sortConfig.value.direction === 'asc' ? 'desc' : 'asc'
    } else {
      sortConfig.value = { key, direction: 'asc' }
    }
  }

  const setFilter = (key, value) => {
    filters.value[key] = value
    currentPage.value = 1
  }

  const clearFilters = () => {
    filters.value = {}
    currentPage.value = 1
  }

  const setData = (newData) => {
    data.value = [...newData]
  }

  const addRow = (row) => {
    data.value.unshift(row)
  }

  const updateRow = (id, updates) => {
    const index = data.value.findIndex(item => item.id === id)
    if (index !== -1) {
      data.value[index] = { ...data.value[index], ...updates }
    }
  }

  const removeRow = (id) => {
    const index = data.value.findIndex(item => item.id === id)
    if (index !== -1) {
      data.value.splice(index, 1)
    }
  }

  const toggleRowSelection = (row) => {
    const index = selectedRows.value.findIndex(r => r.id === row.id)
    if (index !== -1) {
      selectedRows.value.splice(index, 1)
    } else {
      selectedRows.value.push(row)
    }
  }

  const toggleSelectAll = () => {
    if (isSelectedAll.value) {
      selectedRows.value = []
    } else {
      selectedRows.value = [...paginatedData.value]
    }
  }

  const clearSelection = () => {
    selectedRows.value = []
  }

  const goToPage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
      currentPage.value = page
    }
  }

  const nextPage = () => {
    if (currentPage.value < totalPages.value) {
      currentPage.value++
    }
  }

  const prevPage = () => {
    if (currentPage.value > 1) {
      currentPage.value--
    }
  }

  const refresh = async (fetchCallback) => {
    loading.value = true
    error.value = null
    try {
      const newData = await fetchCallback()
      data.value = [...newData]
    } catch (err) {
      error.value = err
    } finally {
      loading.value = false
    }
  }

  const exportData = (format = 'csv') => {
    const exportData = processedData.value
    
    switch (format.toLowerCase()) {
      case 'csv':
        return exportToCSV(exportData)
      case 'json':
        return exportToJSON(exportData)
      default:
        throw new Error(`Unsupported format: ${format}`)
    }
  }

  const exportToCSV = (dataToExport) => {
    if (dataToExport.length === 0) return ''
    
    const headers = Object.keys(dataToExport[0]).join(',')
    const rows = dataToExport.map(row => 
      Object.values(row).map(val => 
        typeof val === 'string' ? `"${val.replace(/"/g, '""')}"` : val
      ).join(',')
    ).join('\n')
    
    return `${headers}\n${rows}`
  }

  const exportToJSON = (dataToExport) => {
    return JSON.stringify(dataToExport, null, 2)
  }

  // Watch for data changes
  watch(data, (newData) => {
    if (currentPage.value > totalPages.value) {
      currentPage.value = Math.max(1, totalPages.value)
    }
  }, { deep: true })

  return {
    // State
    data,
    filters,
    sortConfig,
    selectedRows,
    currentPage,
    itemsPerPage,
    loading,
    error,

    // Computed
    processedData,
    paginatedData,
    totalPages,
    totalItems,
    filteredCount,
    isSelectedAll,
    isIndeterminate,

    // Methods
    sortBy,
    setFilter,
    clearFilters,
    setData,
    addRow,
    updateRow,
    removeRow,
    toggleRowSelection,
    toggleSelectAll,
    clearSelection,
    goToPage,
    nextPage,
    prevPage,
    refresh,
    exportData,
    exportToCSV,
    exportToJSON
  }
}

/**
 * Composable for table column configuration
 */
export function useTableColumns() {
  const columns = ref([])

  const addColumn = (column) => {
    columns.value.push({
      key: column.key,
      label: column.label,
      sortable: column.sortable ?? false,
      width: column.width,
      align: column.align ?? 'left',
      formatter: column.formatter,
      ...column
    })
  }

  const removeColumn = (key) => {
    const index = columns.value.findIndex(col => col.key === key)
    if (index !== -1) {
      columns.value.splice(index, 1)
    }
  }

  const updateColumn = (key, updates) => {
    const index = columns.value.findIndex(col => col.key === key)
    if (index !== -1) {
      columns.value[index] = { ...columns.value[index], ...updates }
    }
  }

  const getColumn = (key) => {
    return columns.value.find(col => col.key === key)
  }

  return {
    columns,
    addColumn,
    removeColumn,
    updateColumn,
    getColumn
  }
}

/**
 * Composable for table filters
 */
export function useTableFilters() {
  const filters = ref({})
  const filterHistory = ref([])

  const setFilter = (key, value) => {
    filters.value[key] = value
    filterHistory.value.push({ key, value, timestamp: new Date() })
  }

  const removeFilter = (key) => {
    delete filters.value[key]
  }

  const clearFilters = () => {
    filters.value = {}
  }

  const getFilter = (key) => {
    return filters.value[key]
  }

  const hasFilter = (key) => {
    return key in filters.value
  }

  const getActiveFilters = () => {
    return Object.entries(filters.value)
      .filter(([, value]) => value != null && value !== '')
      .map(([key, value]) => ({ key, value }))
  }

  const getFilterHistory = () => {
    return [...filterHistory.value].reverse()
  }

  return {
    filters,
    filterHistory,
    setFilter,
    removeFilter,
    clearFilters,
    getFilter,
    hasFilter,
    getActiveFilters,
    getFilterHistory
  }
}

/**
 * Composable for table selection
 */
export function useTableSelection() {
  const selected = ref(new Set())
  const selectMode = ref(false)

  const toggleSelect = (id) => {
    if (selected.value.has(id)) {
      selected.value.delete(id)
    } else {
      selected.value.add(id)
    }
    selectMode.value = selected.value.size > 0
  }

  const select = (id) => {
    selected.value.add(id)
    selectMode.value = true
  }

  const deselect = (id) => {
    selected.value.delete(id)
    selectMode.value = selected.value.size > 0
  }

  const selectAll = (ids) => {
    ids.forEach(id => selected.value.add(id))
    selectMode.value = true
  }

  const deselectAll = () => {
    selected.value.clear()
    selectMode.value = false
  }

  const isSelected = (id) => {
    return selected.value.has(id)
  }

  const getSelected = () => {
    return Array.from(selected.value)
  }

  const getSelectedCount = () => {
    return selected.value.size
  }

  const hasSelection = () => {
    return selected.value.size > 0
  }

  return {
    selected,
    selectMode,
    toggleSelect,
    select,
    deselect,
    selectAll,
    deselectAll,
    isSelected,
    getSelected,
    getSelectedCount,
    hasSelection
  }
}

/**
 * Composable for table sorting
 */
export function useTableSort() {
  const sortConfig = ref({ key: null, direction: 'asc' })

  const setSort = (key, direction = null) => {
    if (sortConfig.value.key === key) {
      if (direction === null) {
        sortConfig.value.direction = sortConfig.value.direction === 'asc' ? 'desc' : 'asc'
      } else {
        sortConfig.value.direction = direction
      }
    } else {
      sortConfig.value = { key, direction: direction || 'asc' }
    }
  }

  const clearSort = () => {
    sortConfig.value = { key: null, direction: 'asc' }
  }

  const sort = (data, key = null, direction = null) => {
    const sortKey = key || sortConfig.value.key
    const sortDirection = direction || sortConfig.value.direction

    if (!sortKey) return data

    return [...data].sort((a, b) => {
      const aVal = a[sortKey]
      const bVal = b[sortKey]

      if (aVal < bVal) return sortDirection === 'asc' ? -1 : 1
      if (aVal > bVal) return sortDirection === 'asc' ? 1 : -1
      return 0
    })
  }

  return {
    sortConfig,
    setSort,
    clearSort,
    sort
  }
}
