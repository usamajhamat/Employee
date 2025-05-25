<template>
  <div ref="chart" class="w-full h-full"></div>
</template>

<script setup>
import { ref, onMounted, watch, onUnmounted } from 'vue';
import * as echarts from 'echarts';

const props = defineProps({
  option: {
    type: Object,
    required: true,
  },
});

const chart = ref(null);
let chartInstance = null;

onMounted(() => {
  chartInstance = echarts.init(chart.value);
  chartInstance.setOption(props.option);
});

watch(
  () => props.option,
  (newOption) => {
    if (chartInstance) {
      chartInstance.setOption(newOption, true);
    }
  },
  { deep: true }
);

onUnmounted(() => {
  if (chartInstance) {
    chartInstance.dispose();
  }
});
</script>