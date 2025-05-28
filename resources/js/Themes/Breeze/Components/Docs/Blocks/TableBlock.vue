<template>
  <div class="flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
        <table :class="tableClasses">
          <thead v-if="withHeadings">
          <tr>
            <th v-for="(cell, index) in content[0]" :key="index"
                class="px-3 py-3.5 text-sm text-gray-900 border border-gray-300">{{ cell }}
            </th>
          </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
          <tr v-for="(row, rowIndex) in content.slice(withHeadings ? 1 : 0)" :key="rowIndex">
            <td v-for="(cell, cellIndex) in row" :key="cellIndex"
                class="px-3 py-3.5 text-sm text-gray-900 border border-gray-300">{{ cell }}
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    stretched: Boolean,
    withHeadings: Boolean,
    content: Array,
  },
  computed: {
    tableClasses() {
      return this.stretched ? 'w-full' : 'max-w-full';
    },
  },
};
</script>
