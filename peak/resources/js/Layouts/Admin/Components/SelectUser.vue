<script setup>
import axios from 'axios';
import {onMounted, ref, watch} from 'vue';
import Multiselect from 'vue-multiselect';

const props = defineProps({
  roles: {
    type: Array,
    default: ['admin', 'super-admin']
  },
  allowEmpty: {
    type: Boolean,
    default: false
  },
  limit: {
    type: Number,
    default: 10, // Default limit if not provided
  },
  preSelectedUser: {
    type: Object,
    default: null,
  },
});

// State variables
const selectedUser = ref(props.preSelectedUser || null);  // Set the pre-selected user, if available
const users = ref([]);
const isLoading = ref(false);

// Fetch users by roles
const fetchUsersByRoles = async (query = '') => {
  isLoading.value = true;
  try {
    const response = await axios.get(route('admin.api.search-users'), {
      params: {search: query, roles: props.roles, limit: props.limit},
    });

    // Ensure the response is an array
    users.value = Array.isArray(response.data) ? response.data : [];
  } catch (error) {
    console.error('Error fetching users:', error);
  } finally {
    isLoading.value = false;
  }
};

// Fetch initial users when the component mounts
onMounted(() => {
  fetchUsersByRoles(); // Initial load with the given roles and limit
});

// Watch for changes in the roles prop and refetch users
watch(
    () => props.roles,
    (newRoles) => {
      if (newRoles && newRoles.length > 0) fetchUsersByRoles(); // Refetch users when the roles change
    }
);

// Watch for changes in the pre-selected user prop
watch(
    () => props.preSelectedUser,
    (newUser) => {
      selectedUser.value = newUser; // Set the new pre-selected user when the prop changes
    }
);

// Emit the selected user to the parent
const emit = defineEmits(['user-selected']);

const handleSelection = (user) => {
  emit('user-selected', user);
};
</script>

<template>
  <div>
    <multiselect id="ajax" v-model="selectedUser" :allow-empty="allowEmpty" :clear-on-select="true"
                 :close-on-select="true"
                 :internal-search="false" :loading="isLoading" :multiple="false" :options="users"
                 :placeholder="__('Type to search')"
                 :searchable="true" label="name" track-by="id"
                 @select="handleSelection"
                 @search-change="fetchUsersByRoles">
      <template #tag="{ option, remove }">
                <span class="custom__tag">
                    <span>{{ option.name }}</span>
                    <span class="custom__remove" @click="remove(option)">❌</span>
                </span>
      </template>
      <template #clear="props">
        <div v-if="selectedUser" class="multiselect__clear" @mousedown.prevent.stop="props.clear()"></div>
      </template>
      <template #noResult>
        <span class="text-xs text-red-600">
          {{ __('No users found. Try adjusting your search.') }}
        </span>
      </template>
    </multiselect>
  </div>
</template>
