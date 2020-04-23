<template>
  
</template>

<script>
export default {
	mounted(){

		this.$alert('解锁密码: 123', '解锁密码', {
			confirmButtonText: '确定',
			callback: action => {
				this.$store.commit('auth/lock', '123');
				this.$router.push({path: '/lock'});
			}
        });

	}
}
</script>