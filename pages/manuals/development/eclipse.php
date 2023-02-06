<?php
	$IMG = $RES_ROOT . "/img/devel/";
?>
<p>LSP Plugins may be easily developed using <a href="https://www.eclipse.org/ide/">Eclipse IDE</a></p>

<h2>Configuring project and build</h2>

<p>First of all, you need to install the following plugins:</p>
<ul>
<li><b>Eclipse C/C++ Development Tools (CDT)</b> - for C/C++ development</li>
<li><b>Eclipse EGit</b> - for GIT integration</li>
</ul>

<p>First of all, you need to checkout GIT repository into your workspace by
issuing the following command:</p>
<pre>
cd &lt;workspace-path&gt;
git clone https://github.com/sadko4u/lsp-plugins.git lsp-plugins
cd lsp-plugins
git checkout -b &lt;your-branch&gt; origin/devel
</pre>

<p>Now we can switch to C++ development perspective by selecting
<b>Window</b> &rarr; <b>Perspective</b> &rarr; <b>Open Perspective</b> &rarr; <b>Other...</b></p>
<div class="images">
	<img src="<?= $IMG ?>eclipse-cpp-perspective.png">
</div>

<p>After switching to C/C++ perspective, select <b>File</b> &rarr; <b>New</b> &rarr; <b>C/C++ Project</b></p>
<p>In the opened dialog, select <b>C++ Managed Build</b> template, then click <b>Next</b></p>
<div class="images">
	<img src="<?= $IMG ?>eclipse-cpp-template.png">
</div>

<p>Enter the folder name same to the folder of the GIT repository, select <b>Executable</b> &rarr; <b>Empty Project</b>,
<b>Linux GCC</b>, then click <b>Finish</b></p>
<div class="images">
	<img src="<?= $IMG ?>eclipse-new-project.png">
</div>

<p>Now, complete project structure is visible in the <b>Project explorer</b> view.</p>
<div class="images">
	<img src="<?= $IMG ?>eclipse-project-explorer.png">
</div>

<p>We just need to configure project build. For this case, select <b>Project</b> &rarr; <b>Properties</b> menu,
the project configuration dialog should appear:</p>
<div class="images">
	<img src="<?= $IMG ?>eclipse-project-properties.png">
</div>

<p>Go to <b>C/C++ Build</b> &rarr; <b>Settings</b> and, first of all, select <b>Configuration</b> combo
to <b>[ All Configurations ]</b>, this will apply all modified settings to all build targets.</p>
<p>Select <b>GCC C++ Compiler</b> &rarr; <b>Dialect</b> section and select the <b>ISO C++98</b> as a <b>Language Standard</b>:</p>
<div class="images">
	<img src="<?= $IMG ?>eclipse-project-lang-std.png">
</div>

<p>Select <b>GCC C++ Compiler</b> &rarr; <b>Preprocessor</b> section and add following macros to <b>Defined Symbols</b>:</p>
<ul>
	<li><b>LSP_TRACE</b> - this macro will enable output of trace information to the console while running code in debug envirionment.</li>
	<li><b>LSP_IDE_DEBUG</b> - makes invisible set of main() functions that are defined in source code of different tools.</li>
	<li><b>LSP_TESTING</b> - enables unit testing subsystem in build.</li>
</ul>
<p>Finally, the window should look like:</p>
<div class="images">
	<img src="<?= $IMG ?>eclipse-preprocessor-setup.png">
</div>

<p>Now we need to define additional location of header files. Select <b>GCC C++ Compiler</b> &rarr; <b>Includes</b>
and add to the <b>Include paths</b> the following line:</p>
<pre>
${workspace_loc:/${ProjName}/include}
</pre>
<p>The window should look like:</p>
<div class="images">
	<img src="<?= $IMG ?>eclipse-project-includes.png">
</div>

<p>Now we need to enable extra headers for additional packages, for this purpose visit <b>GCC C++ Compiler</b> &rarr; <b>Miscellaneous</b>
and enter into the <b>Other flags</b> field the following string:</p>
<pre>
-c -fmessage-length=0 -m64 -msse -msse2 `pkg-config --cflags jack x11 cairo lv2 sndfile gl`
</pre>
<p>Also, check the <b>Position Independent Code (-fPIC)</b> flag:</p>
<div class="images">
	<img src="<?= $IMG ?>eclipse-project-misc.png">
</div>

<p>Select <b>GCC C++ Linker</b> &rarr; <b>General</b> settings and enable the <b>Support for pthread (-pthread)</b> option:<p>
<div class="images">
	<img src="<?= $IMG ?>eclipse-project-linker-general.png">
</div>

<p>Select <b>GCC C++ Linker</b> &rarr; <b>Libraries</b> settings and add the following libraries to the list:
<ul>
	<li>pthread</li>
	<li>dl</li>
</ul>
<div class="images">
	<img src="<?= $IMG ?>eclipse-project-linker-libraries.png">
</div>

<p>Select <b>GCC C++ Linker</b> &rarr; <b>Miscellaneous</b> settings and enter the following string to <b>Linker flags</b> field
to enable linkage with external libraries:<p>
<pre>
`pkg-config --libs jack x11 cairo lv2 sndfile gl`
</pre>
<p>The window should look like:</p>
<div class="images">
	<img src="<?= $IMG ?>eclipse-project-linker-misc.png">
</div>

<p>To make resources generated by makefile invisible to IDE, we need to add <b>.build</b> path as excluded from source tree.
For this purpose, visit <b>Resource</b> &rarr; <b>Resource Filters</b>, click <b>Add Filter</b></p>
<p>Set <b>Filter type</b> to <b>Exclude all</b>, <b>Applies to</b> - <b>Folders</b>, <b>All children (recursive)</b>,
<b>Name</b> <b>Matches</b> <b>.build</b>. The result should look like:</p>
<div class="images">
	<img src="<?= $IMG ?>eclipse-project-exlude-resource.png">
</div>

<p>To optimize build, we can enable more than one thread for build. For this purpose, select
<b>C/C++ Build</b> &rarr; <b>Behavior</b> and click <b>Enable Parallel Build</b>, <b>Use optimal jobs</b>:</p>
<div class="images">
	<img src="<?= $IMG ?>eclipse-project-build.png">
</div>

<p>Now we can click <b>Apply and Close</b> button and launch build by issuing <b>Project</b> &rarr; <b>Build</b> menu item.</p>

<h2>Debugging Project</h2>

<p>To start debugging, simply issue <b>Run</b> &rarr; <b>Debug as</b> &rarr; <b>Local C/C++ Application</b>, Eclipse IDE will
automatically create configuration for launching build that can be reused in future.</p>
<p>The main() function launches testing subsystem, so in console output we will get the following string:</p>
<pre>
USAGE: {utest|ptest|mtest} [args...] [test name...]
</pre>

<p>Now we can edit configuration by selecting <b>Run</b> &rarr; <b>Debug Configurations...</b> and pass additional
parameters to unit testing subsystem. Select the generated <b>lsp-plugins</b> debug profile, <b>Arguments</b> tab
and enter testing subsystem command-line arguments. For example, the following:</p>
<pre>
mtest --nofork --debug standalone --args impulse_reverb_stereo
</pre>
<p>It will look like:</p>
<div class="images">
	<img src="<?= $IMG ?>eclipse-cpp-debug.png">
</div>

<p>Now click <b>Apply</b> and <b>Debug</b> buttons to start debugging. The example command line will start a standalone
<b>Impulse Reverb Stereo</b> plugin that runs under JACK server:</p>
<div class="images">
	<img class="border" src="<?= $IMG ?>eclipse-mtest-plugin.png">
</div>

<p>Happy Development!</p>
