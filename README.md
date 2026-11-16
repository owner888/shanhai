# 山海驭兽录

山海经题材的 3D 异兽收集 RPG，基于 [myphp](https://github.com/owner888/myphp)。

A 3D beast-collecting RPG set in the Classic of Mountains and Seas (山海经), built on [myphp](https://github.com/owner888/myphp).

## 运行

需要 PHP 8.3+ 和 myphp 的检出（默认在本目录旁边的 `../myphp`，可在 `myphp.json` 的 `framework` 里改）。

```sh
myphp run -d macos
```

| 操作 | 键盘 | 手柄 |
|---|---|---|
| 行走 | 方向键 / WASD | 左摇杆 |
| 确认、对话 | 空格 / 回车 / Z | 下方键 |

## 测试

```sh
php tests/run.php
```

规则和数据的测试只需要 PHP，不需要窗口。

## 进度

- [x] M1 地图：3D 图块地图、固定视角、格子移动、门、NPC、告示牌、触发器、事件脚本
- [x] 阶段 0 打地基：数值基线、全书名录与《南山经》图鉴、五行属性表、数值档位
- [ ] 阶段 1 战斗
- [ ] 阶段 2 遇敌、收服、队伍、背包、成长、存档
- [ ] 阶段 3 v0.1：《南山经》全篇

规则见 [docs/rules.md](docs/rules.md)，路线图见 [docs/roadmap.md](docs/roadmap.md)，开发约定见 [AGENTS.md](AGENTS.md)。
