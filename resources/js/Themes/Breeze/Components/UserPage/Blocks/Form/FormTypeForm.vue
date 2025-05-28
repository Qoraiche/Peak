<script setup>

const props = defineProps({
    block: Object,
    editor: Boolean
});

import {onMounted} from 'vue';

onMounted(() => {
    loadTypeformScript();
});

const loadTypeformScript = () => {
    const script = document.createElement("script");
    script.src = "//embed.typeform.com/next/embed.js";
    script.async = true;
    // script.onload = initializeTypeform; // Assuming you have a method to initialize Typeform

    document.head.appendChild(script);
};

</script>

<template>
    <div class="flex items-center justify-center">
        <div class="w-full h-auto">

            <div>
                <div v-show="!editor" :data-tf-widget="block.data.id" data-tf-hide-headers data-tf-hide-footer
                     data-tf-opacity="0"
                     class="overflow-y-auto"
                     style="height:420px;" id="form">
                </div>

                <div class="flex flex-col items-center space-y-3" v-show="editor">
                    <div class="flex justify-center h-[50px] items-center w-full">
                        <svg class="w-6" viewBox="0 0 256 407" version="1.1"
                             xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             preserveAspectRatio="xMidYMid">
                            <g>
                                <path
                                    d="M215.946858,1.30253648 L255.678174,1.30253648 L255.678174,406.328715 L215.946858,406.328715 L215.946858,1.30253648 Z"
                                    fill="#89C6BE"></path>
                                <path
                                    d="M0.408018831,93.5502721 L50.1971144,93.5502721 L50.1971144,0.858266453 L116.183555,0.858266453 L116.183555,93.5502721 L175.808295,93.5502721 L175.808295,144.382168 L116.183555,144.382168 L116.183555,309.440826 C116.183555,316.61085 116.473564,322.799779 117.053584,327.976758 C117.627432,333.166079 118.978754,337.547075 121.101377,341.138258 C123.224001,344.723271 126.401766,347.413572 130.659353,349.209164 C134.89843,351.004755 140.686281,351.899466 148.022907,351.899466 C152.65072,351.899466 157.284703,351.806909 161.912516,351.597115 C166.546499,351.405832 171.174312,350.702405 175.808295,349.511514 L175.808295,402.139002 C168.471669,402.928815 161.332497,403.724799 154.390778,404.533123 C147.442888,405.316766 140.297545,405.724014 132.97326,405.724014 C115.603536,405.724014 101.6152,404.020979 91.0020827,400.645761 C80.3827948,397.258202 72.0959247,392.266334 66.1167905,385.68867 C60.1253155,379.111005 56.0775219,370.836476 53.9548984,360.865082 C51.8261045,350.912199 50.5735098,339.546291 50.1971144,326.785868 L50.1971144,144.382168 L0.408018831,144.382168 L0.408018831,93.5502721"
                                    fill="#33393F"></path>
                            </g>
                        </svg>
                    </div>

                    <span class="font-bold">TypeForm</span>
                </div>
            </div>
        </div>
    </div>

</template>

<style scoped>

</style>
