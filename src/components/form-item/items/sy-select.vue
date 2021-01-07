<template>
    <el-select
        v-model="defaultValue"
        @change="handleChange"
        @clear="handleClear"
        @blur="handleBlur"
        @focus="onFocus"
        :multiple="false"
        :size="propValue.size"
        class="single-select"
        :class="propValue.class"
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
// 列表
export default {
    props: {
        value: '',
        options: false,
        propValue: {
            default(){
                return {
                    placeholder: '请选择',  
                }
            }
        },
    },
    data(){
        return {
            label_name: 'label',
            value_name: 'value',
            defaultValue: this.value,
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
    }
}
</script>

<style lang="scss">
.single-select{
    width: 100%;
}
</style>