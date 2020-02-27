alter table `#__mkv_projects`
    add show_in_thematics boolean not null default 1 comment 'Показывать ли проект в списке тематик',
    add index `#__mkv_projects_show_in_thematics_index`(show_in_thematics);

