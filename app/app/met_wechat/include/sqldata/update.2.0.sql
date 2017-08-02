ALTER TABLE [TABLEPRE]nwechat_user DROP INDEX openid;
ALTER TABLE [TABLEPRE]nwechat_user ADD UNIQUE (openid);
ALTER TABLE [TABLEPRE]nwechat_user ADD usetime int(11);
ALTER TABLE [TABLEPRE]nwechat_user ADD KEY (usetime);