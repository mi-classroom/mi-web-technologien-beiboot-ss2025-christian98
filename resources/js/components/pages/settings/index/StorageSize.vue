<script setup lang="ts">
import {getFileSizeWithUnit} from "@/lib/getFileSizeWithUnit";

const props = defineProps<{
    size: number;
}>();

const [displaySize, unit] = getFileSizeWithUnit(props.size)
</script>

<template>
    {{ new Intl.NumberFormat('en', {}).format(displaySize ?? 0) }} {{ unit }} indexed
</template>
