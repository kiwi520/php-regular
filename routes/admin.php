<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->middleware("paramsDecryption")->group(function () {//用户认证

    Route::prefix('system')->namespace('admin\user')->group(function () {#系统管理

        Route::prefix('permission')->group(function () {##权限管理
            Route::name('permissions')->group(function () { ###角色权限管理
                Route::middleware(['permission:addRole'])->post("addRole", 'PermissionController@addRole');//新增角色
                Route::middleware(['permission:addPermission'])->post("addPermission", 'PermissionController@addPermission');//添加权限
                Route::middleware(['permission:roleAllotPermission'])->post("roleAllotPermission", 'PermissionController@roleAllotPermission');//角色分配权限
                Route::middleware(['permission:permissionAllotRole'])->post("permissionAllotRole", 'PermissionController@permissionAllotRole');//权限分配给角色
                Route::middleware(['permission:roleRevokePermission'])->post("roleRevokePermission", 'PermissionController@roleRevokePermission');//角色取消权限
                Route::middleware(['permission:permissionRevokeRole'])->post("permissionRevokeRole", 'PermissionController@permissionRevokeRole');//权限取消角色
                Route::middleware(['permission:getRole'])->post("getRole", 'PermissionController@getRole');//获取角色
                Route::middleware(['permission:getPermission'])->post("getPermission", 'PermissionController@getPermission');//获取权限
                Route::middleware(['permission:userAssignRole'])->post("userAssignRole", 'PermissionController@userAssignRole');//用户分配角色
            });
        });

        Route::prefix('framework')->group(function () {##组织架构管理
            Route::name('user')->group(function () {###用户管理
                Route::post('login', 'UserController@Login')->withoutMiddleware('auth:api');//登录
                Route::middleware(['permission:logintest'])->get('logintest', 'UserController@test')->withoutMiddleware('auth:api');//登录测试
                Route::post("user", function (Request $request) {//获取用户信息
                    return $request->user();
                });
            });
            Route::name('depart')->group(function () {###部门管理
                Route::middleware(['permission:addDepartMent'])->post("addDepartMent", "DepartmentController@add");//添加部门
                Route::middleware(['permission:departMentList'])->post("departMentList", "DepartmentController@list");//获取部门列表
                Route::middleware(['permission:delDepartMent'])->post("delDepartMent", "DepartmentController@del"); //删除部门
                Route::middleware(['permission:updateDepartMent'])->post("updateDepartMent", "DepartmentController@update"); //编辑部门
            });
            Route::name('posts')->group(function () {###岗位管理
                Route::middleware(['permission:addPosts'])->post("addPosts", "PostsController@add");//添加岗位
                Route::middleware(['permission:postsList'])->post("postsList", "PostsController@list");//岗位列表
                Route::middleware(['permission:delPosts'])->post("delPosts", "PostsController@del"); //删除岗位
                Route::middleware(['permission:updatePosts'])->post("updatePosts", "PostsController@update"); //更新岗位
            });
        });
    });

});
Route::name('admin')->group(function () {
    Route::any("test", "TestController@index");
});
Route::get("loadPermission", "TestController@loadPermission");
Route::get('testForm', function () {
    return view('');
});
// require __DIR__.'/adminUser.php';
