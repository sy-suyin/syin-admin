<template>
<div class="table-toolbar">
        <template v-for="(item, index) in config">
            <el-button
                size="mini"
                :type="item.color || 'primary'"
                :icon="item.icon"
                @click="tableBtnClick(item.type, item.access, item.params)"
                :key="index"
            > {{item.name}} </el-button>
        </template>
    </div>
</template>

<script>
export default {
    props: {
        config: Object,
    }
}
</script>