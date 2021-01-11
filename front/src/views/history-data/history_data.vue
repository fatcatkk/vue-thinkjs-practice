<template>
  <div class="layout">
    <Layout>
    <header>
    <p id="websocket">波形图</p>
    </header>
    </Layout>
    <Layout>
    <Sider class="layout-nav">
      <sideMenu></sideMenu>
    </Sider>
    </Layout>
    <Layout :style="{padding: '0 24px 24px'}">
      <Breadcrumb :style="{margin: '24px 0'}">
          <BreadcrumbItem>Home</BreadcrumbItem>
          <BreadcrumbItem>Components</BreadcrumbItem>
          <BreadcrumbItem>Layout</BreadcrumbItem>
      </Breadcrumb>
      <Content :style="{padding: '24px', minHeight: '280px', background: '#fff'}">
          Content
      </Content>
    </Layout>
  </div>
</template>
<script>
import sideMenu from '../../components/side-menu.vue'

export default {
 components: { sideMenu },
  
}
</script>
<style scoped>
.layout{
    border: 1px solid #d7dde4;
    background: #f5f7f9;
    position: relative;
    border-radius: 4px;
    overflow: hidden;
}
.layout-logo{
    width: 100px;
    height: 30px;
    background: #5b6270;
    border-radius: 3px;
    float: left;
    position: relative;
    top: 15px;
    left: 20px;
}
.layout-nav{
    width: 420px;
    margin: 0 auto;
    margin-right: 20px;
}
</style>