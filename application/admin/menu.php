<?php
/**
 * @author xiayujie <1438641583@qq.com>
 * @date(2019/04/19 20:18 PM)
 */
return [
    [
    	'person/index',
    	'个人中心',
    	'child_node' => [
    		[
                'person/changepwd',
                '修改密码',
                'child_node' => [
                	[
                		'person/changepwd_handle',
                		'提交',
                	],
                ],
            ],
            [
                'person/editinfo',
                '编辑信息',
                'child_node' => [
                	[
                		'person/editinfo_handle',
                		'提交',
                	],
                ],
            ],
    	]
    ],
    [
        'log/default',
        '日志管理',
        'child_node' => [
        	[
        		'log/loginlog',
        		'登录日志',
        		'child_node' => [
        			[
        				'log/delete',
        				'删除',
        			],
        		],
        	],
        	[
                'log/actionlog',
                '操作日志',
                'child_node' => [
                	[
                		'log/deletelog',
                		'删除',
                	],
                ],
        	],
        ],
    ],
    [
    	'users/index',
    	'用户管理',
    	'child_node' => [
    		[
    			'users/add',
    			'添加',
    			'child_node' => [
    				[
    					'users/add_handle',
    					'提交',
    				],
    			],
    		],
    		[
    			'users/edit',
    			'编辑',
    			'child_node' => [
    				[
    					'users/edit_handle',
    					'提交',
    				],
    			],
    		],
    		[
    			'users/delete',
    			'删除',
    		],
    		[
    			'users/enable',
    			'启用',
    		],
    		[
    			'users/disable',
    			'禁用',
    		],
    	],
    ],
    [
    	'config/default',
    	'系统配置',
    	'child_node' => [
            [
                'config/index',
                '基本信息',
            ],
    		[
    			'config/edit',
    			'网站配置',
    			'child_node' => [
    				[
    					'config/edit_handle',
    					'提交',
    				],
    			],
    		],
    		[
    			'config/friendlink',
    			'友情链接',
    			'child_node' => [
    				[
    					'config/link_add',
    					'添加',
    					'child_node' => [
    						[

    							'config/link_add_handle',
    							'提交',
    						],
    					],
    				],
    				[
    					'config/link_edit',
    					'编辑',
    					'child_node' => [
    						[
    							'config/link_edit_handle',
    							'提交',
    						],
    					],
    				],
    				[
    					'config/link_delete',
    					'删除',
    				],
    			],
    		],
    		[
    			'config/carousel_img',
    			'轮播图管理',
    			'child_node' => [
    				[
    					'config/carousel_img_add',
    					'添加',
    					'child_node' => [
    						[
    							'config/carousel_img_add_handle',
    							'提交',
    						],
    					],
    				],
    				[
    					'config/carousel_img_edit',
    					'编辑',
    					'child_node' => [
    						[
    							'config/carousel_img_edit_handle',
    							'提交',
    						],
    					],
    				],
                    [
                        'config/carousel_img_delete',
                        '删除',
                    ],
    			],
    		],
    	],
    ],
    [
        'role/index',
        '角色管理',
        'child_node' => [
            [
                'role/add',
                '添加',
                'child_node' => [
                    [
                        'role/add_handle',
                        '提交',
                    ],
                ],
            ],
            [
                'role/edit',
                '编辑',
                'child_node' => [
                    [
                        'role/edit_handle',
                        '提交',
                    ],
                ],
            ],
            [
                'role/delete',
                '删除',
            ],
            [
                'role/auth',
                '权限设置',
                'child_node' => [
                    [
                        'role/auth_handle',
                        '提交',
                    ],
                ],
            ],
        ],
    ],
    [
        'goods/index',
        '二手物品管理',
        'child_node' => [
            [
                'goods/add',
                '添加',
                'child_node' => [
                    [
                        'goods/add_handle',
                        '提交',
                    ],
                ],
            ],
            [
                'goods/edit',
                '编辑',
                'child_node' => [
                    [
                        'goods/edit_handle',
                        '提交',
                    ],
                ],
            ],
            [
                'goods/delete',
                '删除',
            ],
            [
                'goods/enable',
                '上架',
            ],
            [
                'goods/disable',
                '下架',
            ],
        ],
    ],
    [
        'job/index',
        '兼职招聘管理',
        'child_node' => [
            [
                'job/add',
                '添加',
                'child_node' => [
                    [
                        'job/add_handle',
                        '提交',
                    ],
                ],
            ],
            [
                'job/edit',
                '编辑',
                'child_node' => [
                    [
                        'job/edit_handle',
                        '提交',
                    ],
                ], 
            ],
            [
                'job/delete',
                '删除',
            ],
            [
                'job/enable',
                '开启职位',
            ],
            [
                'job/disable',
                '关闭职位',
            ],
        ],
    ],
    [
        'news/index',
        '资讯管理',
        'child_node' => [
            [
                'news/add',
                '添加',
                'child_node' => [
                    [
                        'news/add_handle',
                        '提交',
                    ],
                ],
            ],
            [
                'news/edit',
                '编辑',
                'child_node' => [
                    [
                        'news/edit_handle',
                        '提交',
                    ],
                ],
            ],
            [
                'news/delete',
                '删除',
            ],
        ],
    ],
];