<script setup>

const props = defineProps({
    modelValue: String | Number | null,
    name: String,
    values: Array // Can accept both an array of strings or an array of objects
});

const emit = defineEmits(["update:modelValue"]);

const updateValue = (event) => {
    emit("update:modelValue", event.target.value);
};

</script>

<template>
    <div>
        <select
            :id="name"
            :name="name"
            @change="updateValue"
            :value="modelValue"
            class="capitalize cursor-pointer block w-full rounded-md focus:bg-gray-50 py-1.5 pl-3 pr-10 text-gray-900 border-1 border-gray-200 ring-0 ring-inset ring-gray-300 focus:ring-0 focus:ring-blue-600 sm:text-sm sm:leading-6">
            <option
                v-for="option in values"
                :key="typeof option === 'object' ? option.key : option"
                :value="typeof option === 'object' ? option.key : option"
            >
                {{ typeof option === 'object' ? option.value : option }}
            </option>
        </select>
    </div>
</template>

<style scoped></style>
