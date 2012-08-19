# Representation of a decal (a svg file) 
#
# The decal knows about its (future) paths and 
# its name.
#
# It will determine its paths using the supplied source and target
# directories. The expected directory structure is expected to be
# the same as it is in sourcepath, i.e /sourcepath/my/svg.svg will
# expect a /targetpath/my/ folder.
#
# @author jpgerdeman
class Decal

	# constructor
	#
	# @param string filepath the path to the decal
	# @param string sourceroot the path to the decal directory
	# @param string targetroot the path to the future decal directory
	def initialize (filepath, sourceroot, targetroot)
		@filepath = filepath
		@sourceroot = sourceroot
		@targetroot = targetroot

		parsename()
	end

	# Shouldn't be called form outside this class.
	#
	# Parse the filename, determine and pretify its parts. 
	def parsename()
		filename = File.basename(@filepath, ".svg")		
		hastlg = filename.include? '.tlg'
		haspar = filename.include? '.('
		if hastlg || haspar
			filename.gsub!('.tlg','');
			@isTLG = true
		else
			@isTLG = false
		end

		@name = filename
	end

	# Returns the path of the svg in source
	#
	# @return string
	def getSourcePath()
		@filepath
	end

	# Returns a pretty name for the file
	#
	# parsename is expected to have been called before, i.e.
	# in the constructor
	#
	# @return string
	def getName()
		@name
	end

	# Return the path of the svg file in the target directory
	#
	# @return string
	def computeTargetPath()
		@filepath.gsub(@sourceroot,@targetroot)
	end

	# Return the path of the full png render in the target directory
	#
	# @return string
	def computePngPath()
		path = computeTargetPath()
		"#{path}.png"
	end
	
	# Return the path of the thumbnail in the target directory
	#
	# @return string
	def computeThumbnailPath()
		path = computeTargetPath()
		"#{path}.tb.png"
	end

	# Returns wether the decal is based on an official Lego design
	#
	# parsename is expected to have been called before, i.e.
	# in the constructor
	#
	# @return bool
	def isTLG()
		@isTLG
	end
end
