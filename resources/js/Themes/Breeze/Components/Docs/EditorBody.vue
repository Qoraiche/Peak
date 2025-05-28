<script setup>
import {defineProps, onMounted} from 'vue';
import ParagraphBlock from '@/Themes/Breeze/Components/Docs/Blocks/ParagraphBlock.vue';
import HeaderBlock from '@/Themes/Breeze/Components/Docs/Blocks/HeaderBlock.vue';
import ImageBlock from '@/Themes/Breeze/Components/Docs/Blocks/ImageBlock.vue';
import QuoteBlock from '@/Themes/Breeze/Components/Docs/Blocks/QuoteBlock.vue';
import CodeBlock from '@/Themes/Breeze/Components/Docs/Blocks/CodeBlock.vue';
import TableBlock from '@/Themes/Breeze/Components/Docs/Blocks/TableBlock.vue';
import AlertBlock from '@/Themes/Breeze/Components/Docs/Blocks/AlertBlock.vue';
import ListBlock from '@/Themes/Breeze/Components/Docs/Blocks/ListBlock.vue';
import InlineCodeBlock from '@/Themes/Breeze/Components/Docs/Blocks/InlineCodeBlock.vue';
import DelimiterBlock from '@/Themes/Breeze/Components/Docs/Blocks/DelimiterBlock.vue';
import LinkBlock from "@/Themes/Breeze/Components/Docs/Blocks/LinkBlock.vue";
import docsearch from '@docsearch/js';
import '@docsearch/css';

const props = defineProps({
  blocks: Array,
});

// Map block types to their respective components
const blockComponents = {
  paragraph: ParagraphBlock,
  header: HeaderBlock,
  image: ImageBlock,
  quote: QuoteBlock,
  code: CodeBlock,
  table: TableBlock,
  linkTool: LinkBlock,
  alert: AlertBlock,
  list: ListBlock,
  inlineCode: InlineCodeBlock,
  delimiter: DelimiterBlock
};

// Method to render block
const renderBlock = (block) => {
  const BlockComponent = blockComponents[block.type];
  return BlockComponent ? BlockComponent : null;
};

onMounted(() => {
  docsearch({
    container: '#searchinputme',
    appId: 'CLMQW2HFQN',
    apiKey: '795c3f551371b2e510a3f5b1c7fd0cdb',
    indexName: 'docs_index',
    placeholder: 'Search docs...',
  })
});

</script>

<template>
  <div>
    <div v-for="block in blocks" :key="block.id">
      <component :is="renderBlock(block)" v-bind="block.data"/>
    </div>
  </div>
</template>

<style scoped>
/* Add any specific styling for EditorBody if needed */
</style>
