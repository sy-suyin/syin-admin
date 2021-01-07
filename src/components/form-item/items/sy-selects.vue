<template>
    <el-select
        v-model="default_value"
        @change="handleChange"
        @clear="handleClear"
        @blur="handleBlur"
        @focus="onFocus"
        @removeTag="handleRemoveTag"
        :multiple="true"
        class="multi-select"
        :class="propValue.class"
        :size="propValue.size"
        :clearable="propValue.clearable"
        :disabled="propValue.disabled"
        :placeholder="propValue.placeholder"
    >
        <el-option
            v-for="item in options"
            :label="item[label_name]"
            :key="item[value_name]"
            :value="item[value_name]"
        >
        </el-option>
    </el-select>
</template>

<script>
// 列表(多选)
export default {
    props: {
        value: '',
        propValue: {
            default(){
                return {
                    placeholder: '请选择',  
                }
            }
        },
        options: false
    },
    data(){
        return {
            label_name: 'label',
            value_name: 'value',
            default_value: this.value,
        }
    },
    mounted(){
        this.label_name = this.propValue.label_name || 'label';
        this.value_name = this.propValue.value_name || 'value';
    },
    methods: {
        handleBlur(event){
            this.$emit('blur', event.detail.value);
        },
        onFocus(){
			this.$emit('focus');
        },
        handleChange(event){
            this.$emit('input', event);
        },
        handleClear(){
			this.$emit('input', '');
        },
        handleRemoveTag(event){
            console.log(event);
        }
    }
}
</script>

<style lang="scss">
.multi-select{
    width: 100%;
}
</style>