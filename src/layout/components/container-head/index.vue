<template>
	<div class="layout-container-header">
		<!-- 此处为顶部导航栏内容 -->
		<div class="navbar">
			<i class="icon el-icon-s-unfold" @click="toggleSidebar" v-if="sidebar_mini"></i>
			<i class="icon el-icon-s-fold" @click="toggleSidebar" v-else></i>

			<div>
				<ul class="navbar-right">
					<li>
						<i class="icon el-icon-lock" @click="lock"></i>
					</li>
					<li>
						<i class="icon el-icon-s-unfold"></i>
					</li>
					<li>
						<el-dropdown @command="userCommand">
							<span class="el-dropdown-link">
								<i class="icon el-dropdown-icon el-icon-user-solid"></i>
							</span>
							<el-dropdown-menu slot="dropdown">
								<el-dropdown-item command="profile">个人中心</el-dropdown-item>
								<el-dropdown-item command="setting">系统设置</el-dropdown-item>
								<el-dropdown-item command="logout">退出登录</el-dropdown-item>
							</el-dropdown-menu>
						</el-dropdown>
					</li>
				</ul>
			</div>
		</div>
	</div>
</template>

<script>
import { mapState } from 'vuex'

export default {
	name: 'container-head',
	props: {
		user: {
			type: Object,
			default: function () {
				return {
					name: '',
					avatar: ''
				}
			}
		}
	},
	data(){
		return {
		}
	},
	methods: {
		//  切换侧边栏状态, 最大/最小化
		toggleSidebar(){
			this.$store.dispatch('settings/changeSetting', {
				key: 'sidebar_mini',
				value: !this.sidebar_mini
			})
		},

		// 锁屏
		lock(){
			this.$prompt('请输入解锁密码', '锁屏提示', {
				confirmButtonText: '确定',
				cancelButtonText: '取消',
				inputPattern: /^[0-9a-zA-Z]{3,16}$/,
				inputErrorMessage: '解锁密码应为3-16位数字, 英文字母的组合'
			}).then(({ value }) => {
				this.$store.commit('auth/lock', value+'');
				this.$router.push({path: '/lock'});
			}).catch(() => {
			});
		},

		userCommand(command){
			// 退出登录
			if(command == 'logout'){
				this.$store.commit('auth/logout');
			}else if(command == 'profile'){
				this.$router.replace({path: '/index/profile'})
			}
		},
	},
	computed: {
		...mapState('settings', {
			sidebar_mini: state =>state.sidebar_mini,
		}),
	}
}
</script>