alter table `#__mkv_projects_thematics`
    add unique index `#__mkv_projects_thematics_projectID_thematicID_uindex` (projectID, thematicID);
