<template>
  <Menu ref="sideMenu" :active-name="$route.name" :open-name="openNames" :theme="menuTheme" width="auto" @on-select="changeMenu">
    <template v-for="item in menuList">
      <MenuItem v-if="item.children.length<=1&&!item.children[0].icon" :name="item.children[0].name" :key="item.id">
        <Icon :type="item.icon" :size="iconSize" :key="item.id"></Icon>
        <span class="layout-text" :key="item.id">{{itemTitle(item)}}</span>
      </MenuItem>
      <Submenu v-if="item.children.length >=1&&item.children[0].icon" :name="item.name" :key="item.id">
        <template slot="title">
          <Icon :type="item.icon" :size="iconSize"></Icon>
          <span class="layout-text">{{itemTitle(item)}}</span>
        </template>
        <template v-for="child in item.children">
          <MenuItem :name="child.name" :key="child.id">
            <Icon :type="child.icon" :size="iconSize" ></Icon>
            <span class="layout-text" >{{ itemTitle(child) }}</span>
          </MenuItem>
        </template>
      </Submenu>
    </template>
  </Menu>
</template>
<script>
export default {
  name:'sidebarMenu',
  props: {
    menuList:Array,
    iconSize:Number,
    menuTheme:{
      type:String,
      default:'dark'
    },
    openNames:{
      type:Array
    }
  },
  methods:{
    changeMenu(active){
      console.log(active);
      this.$emit('on-change',active);//active-name 属性为激活菜单的名字，active作为变量是menuitem的name属性，返回给父组件中on-change事件
    },
    itemTitle(item){
      if (typeof item.title === 'object') {
        return this.$t(item.title.i18n);
      } else {
        return item.title;
      }
    }
  }
}
</script>