import { selectors as constantSelectors } from '../../store/constants';

export const getLanguage = (state) => state.installation.language;
export const isLoading = (state) => state.installation.loading;
export const getSystemInfo = (state) => state.installation.systemInfo;
export const shouldUseCustomCacheFolder = (state) => state.installation.folderSettings.useCustomCacheFolder;
export const getCustomCacheFolder = (state) => state.installation.folderSettings.customCacheFolder;
export const getDbHostname = (state) => state.installation.dbSettings.dbHostname;
export const getDbName = (state) => state.installation.dbSettings.dbName;
export const getDbPort = (state) => state.installation.dbSettings.dbPort;
export const getDbUsername = (state) => state.installation.dbSettings.dbUsername;
export const getDbPassword = (state) => state.installation.dbSettings.dbPassword;
export const getDbTablePrefix = (state) => state.installation.dbSettings.dbTablePrefix;
export const isTablesCreated = (state) => state.installation.dbSettings.dbTablesCreated;
export const getFirstName = (state) => state.installation.adminAccount.firstName;
export const getLastName = (state) => state.installation.adminAccount.lastName;
export const getEmail = (state) => state.installation.adminAccount.email;
export const getUsername = (state) => state.installation.adminAccount.username;
export const getPassword = (state) => state.installation.adminAccount.password;
export const getPassword2 = (state) => state.installation.adminAccount.password2;

export const isConfigFileCreated = (state) => state.installation.configFileCreated;

export const getConfigFileContent = (state) => {
	const { dbHostname, dbName, dbPort, dbUsername, dbPassword, dbTablePrefix } = state.installation.dbSettings;
	const { rootDir } = constantSelectors.getConstants(state);

	const result = window.location.href.match(/(.*)(\/install\/#\/step\d)/);
	const rootUrl = result[1];
	const username = dbUsername.replace(/\$/, '\\$');
	const password = dbPassword.replace(/\$/, '\\$');

	let customCacheFolderRow = '';
	if (shouldUseCustomCacheFolder(state)) {
		customCacheFolderRow = `$g_custom_cache_folder = "${getCustomCacheFolder(state)}";\n`;
	}

	return `<?php

// main program paths - no trailing slashes!
$g_root_url = "${rootUrl}";
$g_root_dir = "${rootDir}";

// database settings
$g_db_hostname = "${dbHostname}";
$g_db_port = "${dbPort}";
$g_db_name = "${dbName}";
$g_db_username = "${username}";
$g_db_password = "${password}";
$g_table_prefix = "${dbTablePrefix}";
${customCacheFolderRow}
?>`;
};

